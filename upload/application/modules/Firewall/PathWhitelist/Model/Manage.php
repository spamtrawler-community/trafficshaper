<?php
class Firewall_PathWhitelist_Model_Manage extends Firewall_Abstract_Model_Manage
{
    protected $dbTableName = 'whitelist_paths';
    public $oInput = NULL;
    protected $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;
    protected $sDbFilterField = 'path';
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
            'id' => md5($this->oInput[0]->path),
            'path' => $this->oInput[0]->path,
            'filter_mode' => $this->oInput[0]->filter_mode,
            'comment' => $this->oInput[0]->comment
        );

        return parent::create();
    }

    public function update()
    {
        $this->aUpdateData = array(
            'path' => $this->oInput[0]->path,
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
        $regEx = "/^(\/(?:(?:(?:(?:[a-zA-Z0-9\\-_.!~*'():\@&=+\$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*)(?:;(?:(?:[a-zA-Z0-9\\-_.!~*'():\@&=+\$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))*)(?:\/(?:(?:(?:[a-zA-Z0-9\\-_.!~*'():\@&=+\$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*)(?:;(?:(?:[a-zA-Z0-9\\-_.!~*'():\@&=+\$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))*))*))$/";

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
                new SpamTrawler_Validate_Regex(array('pattern' => $this->oInput[0]->path));
            } catch(Exception $e) {
                $aErrors = array(
                    'Errors' => 'Invalid Regular Expression!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        } elseif($this->oInput[0]->filter_mode == 'exact') {
            /*if(!preg_match($regEx, $this->oInput[0]->path)){
                $aErrors = array(
                    'Errors' => 'Invalid Path!'
                );
            */
            /*
            if(!is_dir($this->oInput[0]->path)){
                $aErrors = array(
                    'Errors' => 'Invalid Path!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }*/
        } elseif($this->oInput[0]->filter_mode == 'contains') {
        }

        //Validate if path exists
        /*
        if($this->oInput[0]->isregex !== 'true' && !file_exists($this->oInput[0]->path)){
            $aErrors = array(
                'Errors' => 'Path does not exist!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        }*/

        return true;
    }

    public static function validateSettings($aSettings){
        $bErrors = false;
        if(!ctype_digit($aSettings['conf_status'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Path Whitelist Status Invalid!';
        }

        /*
        if(!ctype_digit($aSettings['conf_order'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Path Whitelist Order Invalid!';
        }
        */

        if($bErrors === true){
            return false;
        }
        return true;
    }
}
