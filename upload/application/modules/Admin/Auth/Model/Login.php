<?php
class Admin_Auth_Model_Login
{
    public $sUsername = NULL;
    public $sPassword = NULL;
    public $bDuoSec = TRUE;

    public function login()
    {
        $oTable = new User_Db_Tables_User();

        $select = $oTable->select(SpamTrawler_Db_Table::SELECT_WITH_FROM_PART)
            ->setIntegrityCheck(false);

        $select
            ->where('username = ?', $this->sUsername)
            ->join(SpamTrawler::$Config->database->table->prefix . '_user_groups',
                SpamTrawler::$Config->database->table->prefix . '_user_groups.group_id =' . SpamTrawler::$Config->database->table->prefix . '_user.group_id')
            ->limit(1, 0);
        $rows = $oTable->fetchAll($select);

        $aRows = $rows->toArray();
        $iNumUsers = count($aRows);

        if ($iNumUsers == 1) {
            $sHashIncoming = SpamTrawler_Password::generateHash($this->sPassword, $aRows['0']['salt']);

            if ($sHashIncoming == $aRows['0']['password']) {
                //Set admin details in session
                $_SESSION['iUserID'] = $aRows['0']['id'];
                $_SESSION['sUsername'] = $aRows['0']['username'];
                $_SESSION['sEmail'] = $aRows['0']['email'];
                $_SESSION['iUsergroup'] = $aRows['0']['group_id'];
                $_SESSION['sUserGroupName'] = $aRows['0']['group_name'];
                $_SESSION['twofactor'] = $aRows['0']['twofactor'];

                //Check if DuoSec is activated for admin login
                if (SpamTrawler::$Registry['settings_core']['admin_auth_method'] == 'DuoSecurity' &&
                    !empty(SpamTrawler::$Registry['settings_core']['duosec_akey']) &&
                    !empty(SpamTrawler::$Registry['settings_core']['duosec_ikey']) &&
                    !empty(SpamTrawler::$Registry['settings_core']['duosec_skey']) &&
                    !empty(SpamTrawler::$Registry['settings_core']['duosec_host']) &&
                    $aRows['0']['twofactor'] == 'true'
                ) {
                    header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Auth/DuoSec'));
                } //Check if FidoU2F is activated for admin login
                else if (SpamTrawler::$Registry['settings_core']['admin_auth_method'] == 'U2F' &&
                    $aRows['0']['twofactor'] == 'true'
                ) {
                    $_SESSION['u2f_registered'] = FALSE;
                    if (!empty($aRows['0']['u2f_keyHandle']) && !empty($aRows['0']['u2f_publicKey']) && !empty($aRows['0']['u2f_certificate'])) {
                        $_SESSION['keyHandle'] = $aRows['0']['u2f_keyHandle'];
                        $_SESSION['publicKey'] = $aRows['0']['u2f_publicKey'];
                        $_SESSION['certificate'] = $aRows['0']['u2f_certificate'];
                        $_SESSION['u2f_registered'] = TRUE;
                    }

                    header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Auth/FidoU2F'));
                } else {
                    if (SpamTrawler::$Registry['settings_core']['sysmode'] == 0) {
                        //Add Logged In IP to IP Cache to prevent firewalling
                        SpamTrawler::$Registry['visitordetails']['isAdmin'] = 'yes';
                        SpamTrawler::$Registry['visitordetails']['whitelisted'] = 'yes';
                        SpamTrawler::$Registry['visitordetails']['filterresult'] = 'passed';
                        SpamTrawler::$Registry['oCache']->save(SpamTrawler::$Registry['visitordetails'], sha1(SpamTrawler::$Registry['visitordetails']['ip']), array('ipcache', 'admin'));
                    }
                    header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Dashboard/View'));
                }
                exit();
            }
        }

        $_SESSION['AUTH_ERROR'][] = 'Invalid Username or Password!';
        //$this->aError[] = 'Invalid Username or Password!';
        //User_Auth_Controller_Auth::$aError = $this->aError;
        return FALSE;
    }

    public function validate()
    {
        if (empty($this->sUsername)) {
            $_SESSION['AUTH_ERROR'][] = 'Username missing!';
            //$this->aError[] = 'Username missing!';
        }

        if (empty($this->sPassword)) {
            $_SESSION['AUTH_ERROR'][] = 'Password missing!';
            //$this->aError[] = 'Password missing!';
        }

        if ($_SESSION['AUTH_ERROR']) {
            //User_Auth_Controller_Auth::$aError = $this->aError;
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
