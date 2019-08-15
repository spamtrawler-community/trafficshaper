<?php

class Firewall_URLWhitelist_Model_Manage extends Firewall_Abstract_Model_Manage
{
    protected $dbTableName = 'whitelist_urls';
    protected $dbTableNameValueCache = 'cache_urls';
    protected $dbFieldNameValueVisitor = 'urlID';
    protected $dbFieldNameValueCache = 'url';
    public $oInput = NULL;
    protected $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;
    protected $sDbFilterField = 'url';
    protected $aUpdateData = array();

    public function __construct($oInput = NULL){
        //filter_mode
        if(!is_null($oInput)) {
            if (!$oInput[0]->filter_mode) {
                $oInput[0]->filter_mode = 'exact';
            }
        }
        parent::__construct($oInput);
    }

    public function create()
    {
        $this->aOutput = array(
            'id' => md5($this->oInput[0]->url),
            'url' => $this->oInput[0]->url,
            'filter_mode' => $this->oInput[0]->filter_mode,
            'comment' => $this->oInput[0]->comment
        );

        return parent::create();
    }

    public function update()
    {
        $this->aUpdateData = array(
            'url' => $this->oInput[0]->url,
            'filter_mode' => $this->oInput[0]->filter_mode,
            'comment' => $this->oInput[0]->comment
        );

        return parent::update();
    }

    public function validate()
    {
        if(is_null($this->oInput)){
            $aErrors = array(
                'Errors' => 'Input Data Missing!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        }

        if(isset($this->oInput[0]->id) && !empty($this->oInput[0]->id)){
            if (!ctype_alnum($this->oInput[0]->id)) {
                $aErrors = array(
                    'Errors' => 'Invalid ID!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        }

        //filter_mode
        $aAllowedFilterModes = array('exact','contains','regex');
        if(!in_array($this->oInput[0]->filter_mode, $aAllowedFilterModes)){
            $aErrors = array(
                'Errors' => 'Invalid Filter Mode!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        } elseif($this->oInput[0]->filter_mode == 'regex'){
            try{
                //Instantiate SpamTrawler Validator Regex
                new SpamTrawler_Validate_Regex(array('pattern' => $this->oInput[0]->url));
            } catch(Exception $e) {
                $aErrors = array(
                    'Errors' => 'Invalid Regular Expression!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        } elseif($this->oInput[0]->filter_mode == 'exact') {
            if(!filter_var($this->oInput[0]->url, FILTER_VALIDATE_URL)){
                $aErrors = array(
                    'Errors' => 'Invalid URL!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        }

        return true;
    }

    public static function validateSettings($aSettings){
        $bErrors = false;
        if(!ctype_digit($aSettings['conf_status'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'URL Whitelist Status Invalid!';
        }

        /*
        if(!ctype_digit($aSettings['conf_order'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'URL Whitelist Order Invalid!';
        }
        */

        if($bErrors === true){
            return false;
        }
        return true;
    }
}
