<?php
class User_Manage_Controller_Manage extends SpamTrawler_BaseClasses_Modules_Controller
{
    public $dbTableName = 'user';
    public function __construct(){
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index()
    {
        //Fetching UserGroups
        $oTableGroups = new SpamTrawler_Db_Tables_Generic('user_groups');
        //Instantiate Select
        $oSelect = $oTableGroups->select(SpamTrawler_Db_Table::SELECT_WITH_FROM_PART)
            ->setIntegrityCheck(false);
        $oSelect->reset(SpamTrawler_Db_Select::COLUMNS);
        $oSelect->columns(array('group_id' , 'group_name'));
        $aRowsGroups = $oTableGroups->fetchAll($oSelect)->toArray();

        $sGroupData = '[';
        $sGroupData .= '{ group_name: "Please Choose", group_id: 0 },';
        foreach($aRowsGroups as $row){
                $sGroupData .= '{ group_name: "' . $row['group_name'] .'", group_id: ' . $row['group_id'] . ' },';
        }
        $sGroupData .= ']';

        $this->oSmarty->assign('GroupDataSource', $sGroupData);

        $this->oSmarty->display('Manage.tpl');
        exit();
    }

    public function get()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=utf-8');

        $oManage = new User_Manage_Model_Manage();
        exit($oManage->get());
    }

    public function create()
    {
        //Model constructor expects $oInput to be object
        $oManage = new User_Manage_Model_Manage(json_decode($_POST['models']));

        if(!$oManage->create()){
            exit($oManage->sErrors);
        }

        exit($oManage->sOutput);
    }

    public function update()
    {
        $oManage = new User_Manage_Model_Manage;
        $oManage->oInput = json_decode($_POST['models']);
        $oManage->update();

        if(!is_null($oManage->sErrors)){
            exit($oManage->sErrors);
        }
        exit($oManage->sOutput);
    }

    public function destroy()
    {
        $oManage = new User_Manage_Model_Manage;
        $oManage->oInput = json_decode($_POST['models']);
        $oManage->destroy();

        if(!is_null($oManage->sErrors)){
            exit($oManage->sErrors);
        }
        exit($oManage->sOutput);
    }

    private function validate (){
        return true;
    }
}
