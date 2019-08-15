<?php

class Firewall_HostnameBlacklist_Model_Import extends Firewall_Abstract_Import_Import {
    protected $dbTableName = 'blacklist_hostnames';
    protected $sCheckValueDbField = 'hostname';
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
        } elseif ($this->sFilterMode == 'contains') {
            if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $this->sCheckValue)) {
                $this->aErrors[] = '[Line: ' . $iLine . '] Invalid Hostname';
                return false;
            }
        } else {
            if(!preg_match('/^[a-zA-Z0-9_.-]+$/', $this->sCheckValue)){
                $this->aErrors[] = '[Line: ' . $iLine . '] Invalid Hostname';
                return false;
            }
        }
        return true;
    }
}
