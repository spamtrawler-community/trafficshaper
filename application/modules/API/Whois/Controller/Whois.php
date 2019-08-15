<?php
class API_Whois_Controller_Whois {
    public function index()
    {
        //$sLookup = $_POST['lookup'];
        $sLookup = '64.233.160.5';
        $oWhois = new SpamTrawler_VisitorDetails_Whois_Whois();
        $oWhois->whoislookup($sLookup);
    }
}
