<?php
class User_Auth_Model_Auth
{
    public $sUsername = NULL;
    public $sPassword = NULL;

    public function login()
    {
         $oTable = new User_Db_Tables_User();
         $rows = $oTable->fetchAll(
                       $oTable->select()
                       ->where('username = ?', $this->sUsername)
                       ->limit(1, 0)
         );

        $aRows = $rows->toArray();
        $iNumUsers = count($aRows);

        if($iNumUsers == 1) {
            $sHashIncoming = SpamTrawler_Password::generateHash($this->sPassword, $aRows['0']['salt']);

            if($sHashIncoming == $aRows['0']['password']){
                //Set admin details in session
                $_SESSION['sUsername'] = $aRows['0']['username'];
                $_SESSION['sEmail'] = $aRows['0']['email'];
                $_SESSION['iUsergroup'] = $aRows['0']['group_id'];

                header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Dashboard/View'));
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
        if(empty($this->sUsername)){
            $_SESSION['AUTH_ERROR'][] = 'Username missing!';
            //$this->aError[] = 'Username missing!';
        }

        if(empty($this->sPassword)){
            $_SESSION['AUTH_ERROR'][] = 'Password missing!';
            //$this->aError[] = 'Password missing!';
        }

        if($_SESSION['AUTH_ERROR'])
        {
            //User_Auth_Controller_Auth::$aError = $this->aError;
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function checkPassword()
    {
         if($this->sPassword){

         }
    }
}
