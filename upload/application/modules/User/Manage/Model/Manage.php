<?php
class User_Manage_Model_Manage
{
    public $sUsername = NULL;
    public $sPassword = NULL;
    public $oInput;
    public $sOutput;
    public $aOutput;
    public $sErrors;
    private $aUserGroups = array();
    private $oPurifier;

    public function __construct($oInput = NULL){
        $this->oInput = $oInput;

        //Fetching UserGroups
        $oTableGroups = new SpamTrawler_Db_Tables_Generic('user_groups');
        $oSelect = $oTableGroups->select(SpamTrawler_Db_Table::SELECT_WITH_FROM_PART)
            ->setIntegrityCheck(false);
        $oSelect->reset(SpamTrawler_Db_Select::COLUMNS);
        $oSelect->columns(array('group_name'));
        $aUserGroups = $oTableGroups->fetchAll($oSelect)->toArray();

        foreach($aUserGroups as $key => $value){
            $this->aUserGroups[] = $value['group_name'];
        }
    }

    public function get()
    {
        $oTable = new User_Db_Tables_User();
        return $oTable->returnJSONP();
    }

    public function create()
    {
        if(!$this->validate()){
            return false;
        }
        //Map group_name to group_id
        $oTableGroups = new SpamTrawler_Db_Tables_Generic('user_groups');
        $rowGroups = $oTableGroups->fetchRow(
            $oTableGroups->select()
                ->where('group_name = ?', $this->oInput[0]->group_name)
        );

        $iGroupID = $rowGroups->group_id;

        if(!empty($this->oInput[0]->password)){
            $aData['salt'] = SpamTrawler_Random::generate(9, false, 'luds');
            $aData['password'] = SpamTrawler_Password::generateHash($this->oInput[0]->password, $aData['salt']);
            //$aData['password'] = $this->oInput[0]->password;
        } else {
            $sPasswordClear = SpamTrawler_Random::generate(12, false, 'luds');
            $aData['salt'] = SpamTrawler_Random::generate(9, false, 'luds');
            $aData['password'] = SpamTrawler_Password::generateHash($sPasswordClear, $aData['salt']);
        }

        if($this->oInput[0]->twofactor == 1){
            $this->oInput[0]->twofactor = 'true';
        } else {
            $this->oInput[0]->twofactor = 'false';
        }

        $this->aOutput = array(
            'group_id' => $iGroupID,
            'username' => $this->oInput[0]->username,
            'email' => $this->oInput[0]->email,
            'comment' => $this->oInput[0]->comment,
            'password' => $aData['password'],
            'salt' => $aData['salt'],
            'twofactor' => $this->oInput[0]->twofactor
        );

        try{
        $oTable = new SpamTrawler_Db_Tables_Generic('user');
        $oTable->insert($this->aOutput);
        } catch(Exception $e) {
            $aErrors = array(
                'Errors' => 'Unable to add user!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        }

        //Clear Cache
        //$this->deleteCache();

        $this->sOutput = json_encode($this->aOutput);
        return true;
    }

    /*
    public function create()
    {
        //Map group_name to group_id
        $oTableGroups = SpamTrawler_Db_Tables_les_Generic('user_groups');
        $rowGroups = $oTableGroups->fetchRow(
            $oTableGroups->select()
                ->where('group_name = ?', $this->oInput[0]->group_name)
        );

        $iGroupID = $rowGroups->group_id;

        $aData = array(
            'group_id' => $iGroupID,
            'username' => $this->oInput[0]->username,
            'email' => $this->oInput[0]->email,
            'comment' => $this->oInput[0]->comment
        );

        if(!empty($this->oInput[0]->password)){
            $aData['salt'] = SpamTrawler_Random::generate(9, false, 'luds');
            $aData['password'] = SpamTrawler_Password::generateHash($this->oInput[0]->password, $aData['salt']);
            //$aData['password'] = $this->oInput[0]->password;
        } else {
            $sPasswordClear = SpamTrawler_Random::generate(12, false, 'luds');
            $aData['salt'] = SpamTrawler_Random::generate(9, false, 'luds');
            $aData['password'] = SpamTrawler_Password::generateHash($sPasswordClear, $aData['salt']);
        }

        $oTable =SpamTrawler_Db_Tables_bles_User();

        $oTable->insert($aData);

        $this->sOutput = json_encode($this->oInput);
    }
    */

    public function update()
    {
        if(!$this->validate()){
            return false;
        }

        $oTableGroups = new SpamTrawler_Db_Tables_Generic('user_groups');
        $rowGroups = $oTableGroups->fetchRow(
            $oTableGroups->select()
                ->where('group_name = ?', $this->oInput[0]->group_name)
        );

        $iGroupID = $rowGroups->group_id;

        /*
        switch($this->oInput[0]->group_name){
            case 'Administrators':
                $iGroupID = 1;
                break;

            default:
                $iGroupID = 2;
        }
        */

        if($this->oInput[0]->twofactor == 1){
            $this->oInput[0]->twofactor = 'true';
        } else {
            $this->oInput[0]->twofactor = 'false';
        }

        $aData = array(
            'group_id' => $iGroupID,
            'username' => $this->oInput[0]->username,
            'email' => $this->oInput[0]->email,
            'comment' => $this->oInput[0]->comment,
            'twofactor' => $this->oInput[0]->twofactor
        );

        if(!empty($this->oInput[0]->password)){
            $aData['salt'] = SpamTrawler_Random::generate(9, false, 'luds');
            $aData['password'] = SpamTrawler_Password::generateHash($this->oInput[0]->password, $aData['salt']);
            //$aData['password'] = $this->oInput[0]->password;
        }

        $oTable = new User_Db_Tables_User();

        $where = $oTable->getAdapter()->quoteInto('id = ?', $this->oInput[0]->id);

        $oTable->update($aData, $where);

        $this->sOutput = json_encode($this->oInput);
    }

    public function destroy()
    {
        if(!ctype_digit($this->oInput[0]->id)){
            $this->sErrors = 'Invalid User ID';
            return false;
        }

        $oTable = new User_Db_Tables_User();

        $where = $oTable->getAdapter()->quoteInto('id = ?', $this->oInput[0]->id);

        $oTable->delete($where);

        $this->sOutput = json_encode($this->oInput);
    }

    private function validate(){
        if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $this->oInput[0]->username) ){
            $aErrors = array(
                'Errors' => '<u><strong>Invalid Username</strong></u><br />
                Length: 6-32 characters<br/>
                Characters: Alphanumeric<br/>
                Must begin with letter'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        }

        if(!empty($this->oInput[0]->password) && !preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $this->oInput[0]->password)){
            $aErrors = array(
                'Errors' => '<u><strong>Invalid Password</strong></u><br />
                Length: min. 8 characters<br/>
                Characters: Alphanumeric, Special Characters<br/><br />
                <u><strong>Complexity required:</strong></u><br />
                At least 1 lowercase and uppercase letter<br />
                At least 1 digit<br />
                At least 1 special character (e.g.: @$"%^&*_)'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        }

        if(!in_array($this->oInput[0]->group_name, $this->aUserGroups)){
            $aErrors = array(
                'Errors' => 'Please Choose User Group!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        }

        if(!filter_var($this->oInput[0]->email, FILTER_VALIDATE_EMAIL)){
            $aErrors = array(
                'Errors' => 'Email Address Invalid!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        }

        if(!empty($this->oInput[0]->twofactor) && trim($this->oInput[0]->twofactor) !== '1'){
            $aErrors = array(
                'Errors' => 'Invalid value for Two Factor Authentication!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        }


        return true;
    }
}
