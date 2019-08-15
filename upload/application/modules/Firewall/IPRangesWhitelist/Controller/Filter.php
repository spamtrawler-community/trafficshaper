<?php

class Firewall_IPRangesWhitelist_Controller_Filter extends Firewall_Abstract_Whitelists_Filter{
    protected $aParams = NULL;
    protected $sDbTableName = 'whitelist_ipranges';
    //protected $sDbFieldNameStart = 'range_start';
    //protected $sDbFieldNameEnd = 'range_end';
    //protected $oTable = NULL;
    protected $sFilterType = NULL;
    protected $sCheckValue = NULL;
    protected $bAllowRegex = FALSE;
    protected $bLog = true;

    public function __construct($sParams, $aFirewallSettings){
        parent::__construct($sParams, $aFirewallSettings);
    }

    public function filter(){
        if(!SpamTrawler_VisitorDetails_IP_IP::isV6()){
            try {
                $select = $this->oTable->select()
                    ->order('id DESC')
                    ->where("`range_start_long` <= ?", SpamTrawler_VisitorDetails_IP_IP::getLong())
                    ->where("`range_end_long` >= ?", SpamTrawler_VisitorDetails_IP_IP::getLong());
                $count = count($this->oTable->fetchAll($select)->toArray());

                if ($count > 0){
                    $this->permit();
                }
            } catch (Exception $e){
                exit('Exception : An Error occurred in Module: IPRanges Whitelist');
            }
        } else {
            //IPv6 Range Check
            $sql = $this->oTable->select();
            $aResult = $this->oTable->fetchAll($sql)->toArray();

            foreach($aResult as $key => $value){
                $ip = SpamTrawler_VisitorDetails_IP_IP::getIppton();

                if ((strlen($ip) == strlen(inet_pton($value['range_start'])))
                    &&  ($ip >= inet_pton($value['range_start']) && $ip <= inet_pton($value['range_end']))) {
                    $this->permit();
                }
            }
        }

    }
}
