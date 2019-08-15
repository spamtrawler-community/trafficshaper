<?php

class Firewall_PathWhitelist_Model_Import extends Firewall_Abstract_Import_Import {
    protected $dbTableName = 'whitelist_paths';
    protected $sCheckValueDbField = 'path';
    protected $sManagerClass;

    public function __construct(){
        $aModelClass = explode('_', __CLASS__);
        $this->sManagerClass = 'Firewall_' . $aModelClass[1] . '_Model_Manage';
    }

    protected function prepareData(){
        $aData = array(
            'id' => md5($this->sCheckValue),
            $this->sCheckValueDbField => $this->sCheckValue,
            'filter_mode' => $this->sFilterMode,
            'comment' => ''
        );

        return $aData;
    }

    protected function validate($iLine){
        $aFilterModesAllowed = array('exact', 'contains', 'regex');
        if(!in_array($this->sFilterMode, $aFilterModesAllowed)){
            $this->aErrors[] = '[Line: ' . $iLine . '] Invalid Filter Mode';
            return false;
        }

        if ($this->sFilterMode == 'regex') {
            try {
                new SpamTrawler_Validate_Regex(array('pattern' => $this->sCheckValue));
            } catch (Exception $e) {
                $this->aErrors[] = '[Line: ' . $iLine . '] Invalid Regular Expression';
                return false;
            }
        } elseif($this->sFilterMode == 'exact' || $this->sFilterMode == 'contains'){
            /*filter_mode
            $regEx = "/^(\/(?:(?:(?:(?:[a-zA-Z0-9\\-_.!~*'():\@&=+\$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*)(?:;(?:(?:[a-zA-Z0-9\\-_.!~*'():\@&=+\$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))*)(?:\/(?:(?:(?:[a-zA-Z0-9\\-_.!~*'():\@&=+\$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*)(?:;(?:(?:[a-zA-Z0-9\\-_.!~*'():\@&=+\$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))*))*))$/";
            if(!preg_match($regEx, $this->sCheckValue)){
                $this->aErrors[] = '[Line: ' . $iLine . '] Invalid Path';
                return false;
            }*/
        }
        return true;
    }
}
