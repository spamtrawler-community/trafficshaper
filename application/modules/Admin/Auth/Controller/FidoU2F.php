<?php
class Admin_Auth_Controller_FidoU2F extends SpamTrawler_BaseClasses_Modules_Controller {
    private $oU2F;
    private $sJavaScript = '';

    public function __construct(){
        //Exclude from Maintenance
        $this->bExcludeFromMaintenance = TRUE;

        if(!isset($_SESSION['sUsername']) || !isset($_SESSION['sEmail']) || !isset($_SESSION['iUsergroup']) || $_SESSION['TwoFactorAuthStatus']){
            header('HTTP/1.0 403 Forbidden');
            exit('Invalid Access Attempt!');
        }

        $scheme = isset($_SERVER['HTTPS']) ? "https://" : "http://";
        $this->oU2F = new Admin_Auth_Helper_FidoU2F($scheme . $_SERVER['HTTP_HOST']);

        parent::__construct();
    }

    private function createAndGetUser(){
        $sUser = array(
            'id' =>  $_SESSION['iUserID'],
            'name' => $_SESSION['sUsername']
        );
        $oUser = new stdClass();
        foreach ($sUser as $key => $value)
        {
            $oUser->$key = $value;
        }

        return $oUser;
    }

    public function getRegs(){
        if(!$_SESSION['u2f_registered']){
            $data= array();
        } else {
            $array = array(array(
                'id' => '1',
                'user_id' =>  $_SESSION['iUserID'],
                'keyHandle' => $_SESSION['keyHandle'],
                'publicKey' => $_SESSION['publicKey'],
                'certificate' => $_SESSION['certificate'],
                'counter' => '1'
            ));

            $data = json_encode($array);
            $data = json_decode($data);
        }

        return $data;
    }

    private function addReg($user_id, $reg) {
        $aData = array(
            'u2f_keyHandle' => $reg->keyHandle,
            'u2f_publicKey' => $reg->publicKey,
            'u2f_certificate' => $reg->certificate,
        );

        $oTableUser = new User_Db_Tables_User();
        $where = $oTableUser->getAdapter()->quoteInto('id = ?', $user_id);
        $oTableUser->update($aData, $where);

        $_SESSION['u2f_registered'] = TRUE;
        $_SESSION['keyHandle'] = $reg->keyHandle;
        $_SESSION['publicKey'] = $reg->publicKey;
        $_SESSION['certificate'] = $reg->certificate;

    }

    public function index(){
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!$_POST['username']) {
                $this->sJavaScript .= "alert('no username provided!');";
            } elseif($_POST['username'] != $_SESSION['sUsername']){
                $this->sJavaScript .= "alert('Username does not match authenticated user!');";
            } else if(!isset($_POST['action']) && !isset($_POST['register2']) && !isset($_POST['authenticate2'])) {
                $this->sJavaScript .= "alert('no action provided!');";
            } else {
                $user = $this->createAndGetUser($_POST['username']);

                if(isset($_POST['action'])) {
                    switch($_POST['action']):
                        case 'register':
                            try {
                                //var_dump($this->getRegs($user->id));
                                //exit();

                                $data = $this->oU2F->getRegisterData($this->getRegs($user->id));

                                list($req,$sigs) = $data;
                                $_SESSION['regReq'] = json_encode($req);
                                $this->sJavaScript .= "var req = " . json_encode($req) . ";";
                                $this->sJavaScript .= "var sigs = " . json_encode($sigs) . ";";
                                $this->sJavaScript .= "var username = '" . $user->name . "';";
                                $this->sJavaScript .=
<<<"EOD"
        setTimeout(function() {
            console.log("Register: ", req);
            u2f.register([req], sigs, function(data) {
                var form = document.getElementById('form');
                var reg = document.getElementById('register2');
                var user = document.getElementById('username');
                console.log("Register callback", data);
                if(data.errorCode) {
                    alert("registration failed with errror: " + data.errorCode);
                    return;
                }
                reg.value = JSON.stringify(data);
                user.value = username;
                $( "#action" ).remove();
                form.submit();
            });
        }, 1000);
EOD;


                            } catch( Exception $e ) {
                                $this->sJavaScript .= "alert('error: " . $e->getMessage() . "');";
                            }

                            break;

                        case 'authenticate':
                            try {
                                $reqs = json_encode($this->oU2F->getAuthenticateData($this->getRegs($user->id)));

                                $_SESSION['authReq'] = $reqs;
                                $this->sJavaScript .= "var req = $reqs;";
                                $this->sJavaScript .= "var username = '" . $user->name . "';";
                                $this->sJavaScript .=
<<<"EOD"
          setTimeout(function() {
            console.log("sign: ", req);
            u2f.sign(req, function(data) {
                var form = document.getElementById('form');
                var auth = document.getElementById('authenticate2');
                var user = document.getElementById('username');
                console.log("Authenticate callback", data);
                auth.value=JSON.stringify(data);
                user.value = username;
                $( "#action" ).remove();
                form.submit();
            });
        }, 1000);
EOD;


                            } catch( Exception $e ) {
                                $this->sJavaScript .= "alert('error: " . $e->getMessage() . "');";
                            }

                            break;

                    endswitch;

                } else if($_POST['register2']) {
                    try {
                        $reg = $this->oU2F->doRegister(json_decode($_SESSION['regReq']), json_decode($_POST['register2']));
                        $this->addReg($user->id, $reg);
                    } catch( Exception $e ) {
                        $this->sJavaScript .= "alert('error: " . $e->getMessage() . "');";
                    } finally {
                        $_SESSION['regReq'] = null;
                    }
                } else if($_POST['authenticate2']) {
                    try {
                        $reg = $this->oU2F->doAuthenticate(json_decode($_SESSION['authReq']), $this->getRegs($user->id), json_decode($_POST['authenticate2']));
                        $_SESSION['TwoFactorAuthStatus'] = TRUE;
                        unset($_SESSION['keyHandle'], $_SESSION['publicKey'], $_SESSION['certificate']);
                        header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Dashboard/View'));
                        //$this->updateReg($reg);
                        //$this->sJavaScript .= "alert('success: " . $reg->counter . "');";
                    } catch( Exception $e ) {
                        $this->sJavaScript .= "alert('error: " . $e->getMessage() . "');";
                    } finally {
                        $_SESSION['authReq'] = null;
                    }
                }
            }
        }

        if($_SESSION['u2f_registered']){
            $sMode = 'authenticate';
        } else {
            $sMode = 'register';
        }

        $this->oSmarty->assign('mode', $sMode);
        $this->oSmarty->assign('btnCaption', ucfirst($sMode));
        $this->oSmarty->assign('U2FRegistered', $_SESSION['u2f_registered']);
        $this->oSmarty->assign('username', $_SESSION['sUsername']);
        $this->oSmarty->assign('javascript', $this->sJavaScript);
        $this->oSmarty->display('FidoU2F.tpl');
    }
}
