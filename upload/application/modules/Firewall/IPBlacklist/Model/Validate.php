<?php

class Firewall_IPBlacklist_Model_Validate
{
    public $aErrors = array();

    public function IP($ip)
    {
        $validator = new Zend_Validate_Ip();
        if (!$validator->isValid($ip)) {
            $errors = array(
                'Errors' => 'Invalid IP Address!'
            );
            $this->aErrors = $errors;
        }
    }
}
