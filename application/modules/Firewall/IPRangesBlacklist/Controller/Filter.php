<?php
class Firewall_IPRangesBlacklist_Controller_Filter extends Firewall_Abstract_Blacklists_Filter
{
    protected $aParams = NULL;
    protected $sDbTableName = 'blacklist_ipranges';
    //protected $sDbFieldNameStart = 'range_start';
    //protected $sDbFieldNameEnd = 'range_end';
    //protected $oTable = NULL;
    protected $sFilterType = NULL;
    protected $sCheckValue = NULL;
    protected $bAllowRegex = FALSE;

    public function __construct($sParams, $aFirewallSettings)
    {
        $this->sFilterClass = __CLASS__;
        parent::__construct($sParams, $aFirewallSettings);
    }

    public function filter()
    {
        if (Firewall_Core_Controller_Filter::$bVisitorIsCached === false) {
            if (!SpamTrawler_VisitorDetails_IP_IP::isV6()) {
                try {
                    $select = $this->oTable->select()
                        ->order('id DESC')
                        ->where("range_start_long <= ?", SpamTrawler_VisitorDetails_IP_IP::getLong())
                        ->where("range_end_long >= ?", SpamTrawler_VisitorDetails_IP_IP::getLong());
                    $count = count($this->oTable->fetchAll($select)->toArray());

                    if ($count > 0) {
                        $this->block();
                    }
                } catch (Exception $e) {
                    exit('Exception : An Error occurred in Module: IPRanges Blacklist');
                }
            } else {
                //IPv6 Range Check
                $sql = $this->oTable->select();
                $aResult = $this->oTable->fetchAll($sql)->toArray();

                foreach ($aResult as $key => $value) {
                    $ip = SpamTrawler_VisitorDetails_IP_IP::getIppton();

                    if ((strlen($ip) == strlen(inet_pton($value['range_start'])))
                        && ($ip >= inet_pton($value['range_start']) && $ip <= inet_pton($value['range_end']))
                    ) {
                        $this->block();
                    }
                }
            }
        }

    }
}
