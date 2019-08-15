<?php

class Firewall_TestBlacklist_Controller_Filter extends Firewall_Abstract_Blacklists_Filter
{
    protected $aParams = NULL;
    protected $sDbTableName = 'blacklist_organizations';
    protected $sDbFieldName = 'organization';
    protected $oTable = NULL;
    protected $sFilterType = 'cache';
    protected $sCheckValue = NULL;

    public function __construct($sParams, $aFirewallSettings){
        $this->export_modules();
        exit();
    }

    private function export_modules()
    {
        $oSettings = new SpamTrawler_Db_Tables_Settings();

        $sql = $oSettings->select()
            ->where('conf_group = ?', 'modules')
            ->where('conf_module = ?', 'firewall')
            ->where('conf_category = ?', 'blacklist')
            ->order(array('conf_order ASC'));

        $aRows = $oSettings->fetchAll($sql);

        foreach($aRows as $module){
            $aModule = array(
                'conf_group' => $module->conf_group,
                'conf_module' => $module->conf_module,
                'conf_category' => $module->conf_category,
                'conf_name' => $module->conf_name,
                'conf_params' => unserialize($module->conf_params),
                'conf_class_name' => $module->conf_class_name,
                'conf_group_order' => $module->conf_group_order,
                'conf_order' => $module->conf_order
            );

            //echo json_encode($aModule);
            echo '<pre>' . json_encode($aModule, JSON_PRETTY_PRINT) . '</pre>';
        }
    }
}
