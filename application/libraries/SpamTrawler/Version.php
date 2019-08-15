<?php
final class SpamTrawler_Version
{
    private static $sProductName = 'SpamTrawler Community Edition';
    private static $sVersion = '6.1';
    private static $sCodeName = 'Phoenix';

    public static function getPoweredBy()
    {
        return 'Powered by ' . self::$sProductName . ' v' . self::$sVersion . ' ' . '(' .self::getCodename() . ')';
    }

    public static function getVersion()
    {
        return self::$sVersion;
    }

    public static function getCodename()
    {
        return self::$sCodeName;
    }

    public static function getCopyright()
    {
        return '';
    }
}
