<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 01/07/14
 * Time: 13:05
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler) 
 */

class SpamTrawler_VisitorDetails_GeoIP_GeoIP {

    public static $aCountryInfo = array('A1' => array("CountryName" => "Anonymous Proxy","ContinentIso" => "--","ContinentName" => "--", "latitude" => "--","longitude" => "--"),
        'A2' => array("CountryName" => "Satellite Provider","ContinentIso" => "--","ContinentName" => "--", "latitude" => "--","longitude" => "--"),
        'AD' => array("CountryName" => "Andorra","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "42.5000","longitude" => "1.5000"),
        'AE' => array("CountryName" => "United Arab Emirates","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "24.0000","longitude" => "54.0000"),
        'AF' => array("CountryName" => "Afghanistan","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "33.0000","longitude" => "65.0000"),
        'AG' => array("CountryName" => "Antigua and Barbuda","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "17.0500","longitude" => "-61.8000"),
        'AI' => array("CountryName" => "Anguilla","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "18.2500","longitude" => "-63.1667"),
        'AL' => array("CountryName" => "Albania","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "41.0000","longitude" => "20.0000"),
        'AM' => array("CountryName" => "Armenia","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "40.0000","longitude" => "45.0000"),
        'AN' => array("CountryName" => "Netherlands Antilles","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "12.2500","longitude" => "-68.7500"),
        'AO' => array("CountryName" => "Angola","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-12.5000","longitude" => "18.5000"),
        'AP' => array("CountryName" => "Asia/Pacific Region","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "35.0000","longitude" => "105.0000"),
        'AQ' => array("CountryName" => "Antarctica","ContinentIso" => "AN","ContinentName" => "Antarctica","latitude" => "-90.0000","longitude" => "0.0000"),
        'AR' => array("CountryName" => "Argentina","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "-34.0000","longitude" => "-64.0000"),
        'AS' => array("CountryName" => "American Samoa","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-14.3333","longitude" => "-170.0000"),
        'AT' => array("CountryName" => "Austria","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "47.3333","longitude" => "13.3333"),
        'AU' => array("CountryName" => "Australia","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-27.0000","longitude" => "133.0000"),
        'AW' => array("CountryName" => "Aruba","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "12.5000","longitude" => "-69.9667"),
        'AX' => array("CountryName" => "Aland Islands","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "60.1500","longitude" => "20.0000"),
        'AZ' => array("CountryName" => "Azerbaijan","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "40.5000","longitude" => "47.5000"),
        'BA' => array("CountryName" => "Bosnia and Herzegovina","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "44.0000","longitude" => "18.0000"),
        'BB' => array("CountryName" => "Barbados","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "13.1667","longitude" => "-59.5333"),
        'BD' => array("CountryName" => "Bangladesh","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "24.0000","longitude" => "90.0000"),
        'BE' => array("CountryName" => "Belgium","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "50.8333","longitude" => "4.0000"),
        'BF' => array("CountryName" => "Burkina Faso","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "13.0000","longitude" => "-2.0000"),
        'BG' => array("CountryName" => "Bulgaria","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "43.0000","longitude" => "25.0000"),
        'BH' => array("CountryName" => "Bahrain","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "26.0000","longitude" => "50.5500"),
        'BI' => array("CountryName" => "Burundi","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-3.5000","longitude" => "30.0000"),
        'BJ' => array("CountryName" => "Benin","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "9.5000","longitude" => "2.2500"),
        'BL' => array("CountryName" => "Saint Bartelemey","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "17.9000","longitude" => "-62.8333"),
        'BM' => array("CountryName" => "Bermuda","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "32.3333","longitude" => "-64.7500"),
        'BN' => array("CountryName" => "Brunei Darussalam","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "4.5000","longitude" => "114.6667"),
        'BO' => array("CountryName" => "Bolivia","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "-17.0000","longitude" => "-65.0000"),
        'BR' => array("CountryName" => "Brazil","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "-10.0000","longitude" => "-55.0000"),
        'BS' => array("CountryName" => "Bahamas","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "24.2500","longitude" => "-76.0000"),
        'BT' => array("CountryName" => "Bhutan","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "27.5000","longitude" => "90.5000"),
        'BV' => array("CountryName" => "Bouvet Island","ContinentIso" => "AN","ContinentName" => "Antarctica","latitude" => "-54.4333","longitude" => "3.4000"),
        'BW' => array("CountryName" => "Botswana","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-22.0000","longitude" => "24.0000"),
        'BY' => array("CountryName" => "Belarus","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "53.0000","longitude" => "28.0000"),
        'BZ' => array("CountryName" => "Belize","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "17.2500","longitude" => "-88.7500"),
        'CA' => array("CountryName" => "Canada","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "60.0000","longitude" => "-95.0000"),
        'CC' => array("CountryName" => "Cocos (Keeling) Islands","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "-12.5000","longitude" => "96.8333"),
        'CD' => array("CountryName" => "Congo The Democratic Republic of the","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "0.0000","longitude" => "25.0000"),
        'CF' => array("CountryName" => "Central African Republic","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "7.0000","longitude" => "21.0000"),
        'CG' => array("CountryName" => "Congo","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-1.0000","longitude" => "15.0000"),
        'CH' => array("CountryName" => "Switzerland","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "47.0000","longitude" => "8.0000"),
        'CI' => array("CountryName" => "Cote d'Ivoire","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "8.0000","longitude" => "-5.0000"),
        'CK' => array("CountryName" => "Cook Islands","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-21.2333","longitude" => "-159.7667"),
        'CL' => array("CountryName" => "Chile","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "-30.0000","longitude" => "-71.0000"),
        'CM' => array("CountryName" => "Cameroon","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "6.0000","longitude" => "12.0000"),
        'CN' => array("CountryName" => "China","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "35.0000","longitude" => "105.0000"),
        'CO' => array("CountryName" => "Colombia","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "4.0000","longitude" => "-72.0000"),
        'CR' => array("CountryName" => "Costa Rica","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "10.0000","longitude" => "-84.0000"),
        'CU' => array("CountryName" => "Cuba","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "21.5000","longitude" => "-80.0000"),
        'CV' => array("CountryName" => "Cape Verde","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "16.0000","longitude" => "-24.0000"),
        'CX' => array("CountryName" => "Christmas Island","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "-10.5000","longitude" => "105.6667"),
        'CY' => array("CountryName" => "Cyprus","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "35.0000","longitude" => "33.0000"),
        'CZ' => array("CountryName" => "Czech Republic","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "49.7500","longitude" => "15.5000"),
        'DE' => array("CountryName" => "Germany","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "51.0000","longitude" => "9.0000"),
        'DJ' => array("CountryName" => "Djibouti","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "11.5000","longitude" => "43.0000"),
        'DK' => array("CountryName" => "Denmark","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "56.0000","longitude" => "10.0000"),
        'DM' => array("CountryName" => "Dominica","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "15.4167","longitude" => "-61.3333"),
        'DO' => array("CountryName" => "Dominican Republic","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "19.0000","longitude" => "-70.6667"),
        'DZ' => array("CountryName" => "Algeria","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "28.0000","longitude" => "3.0000"),
        'EC' => array("CountryName" => "Ecuador","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "-2.0000","longitude" => "-77.5000"),
        'EE' => array("CountryName" => "Estonia","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "59.0000","longitude" => "26.0000"),
        'EG' => array("CountryName" => "Egypt","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "27.0000","longitude" => "30.0000"),
        'EH' => array("CountryName" => "Western Sahara","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "24.5000","longitude" => "-13.0000"),
        'ER' => array("CountryName" => "Eritrea","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "15.0000","longitude" => "39.0000"),
        'ES' => array("CountryName" => "Spain","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "40.0000","longitude" => "-4.0000"),
        'ET' => array("CountryName" => "Ethiopia","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "8.0000","longitude" => "38.0000"),
        'EU' => array("CountryName" => "Europe","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "47.0000","longitude" => "8.0000"),
        'FI' => array("CountryName" => "Finland","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "64.0000","longitude" => "26.0000"),
        'FJ' => array("CountryName" => "Fiji","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-18.0000","longitude" => "175.0000"),
        'FK' => array("CountryName" => "Falkland Islands (Malvinas)","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "-51.7500","longitude" => "-59.0000"),
        'FM' => array("CountryName" => "Micronesia Federated States of","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "6.9167","longitude" => "158.2500"),
        'FO' => array("CountryName" => "Faroe Islands","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "62.0000","longitude" => "-7.0000"),
        'FR' => array("CountryName" => "France","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "46.0000","longitude" => "2.0000"),
        'FX' => array("CountryName" => "France (Reserved Metropolitan)","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "46.0000","longitude" => "2.0000"),
        'GA' => array("CountryName" => "Gabon","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-1.0000","longitude" => "11.7500"),
        'GB' => array("CountryName" => "United Kingdom","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "54.0000","longitude" => "-2.0000"),
        'GD' => array("CountryName" => "Grenada","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "12.1167","longitude" => "-61.6667"),
        'GE' => array("CountryName" => "Georgia","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "42.0000","longitude" => "43.5000"),
        'GF' => array("CountryName" => "French Guiana","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "4.0000","longitude" => "-53.0000"),
        'GG' => array("CountryName" => "Guernsey","ContinentIso" => "EU","ContinentName" => "Europe", "latitude" => "49.4500","longitude" => "-2.5800"),
        'GH' => array("CountryName" => "Ghana","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "8.0000","longitude" => "-2.0000"),
        'GI' => array("CountryName" => "Gibraltar","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "36.1833","longitude" => "-5.3667"),
        'GL' => array("CountryName" => "Greenland","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "72.0000","longitude" => "-40.0000"),
        'GM' => array("CountryName" => "Gambia","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "13.4667","longitude" => "-16.5667"),
        'GN' => array("CountryName" => "Guinea","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "11.0000","longitude" => "-10.0000"),
        'GP' => array("CountryName" => "Guadeloupe","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "16.2500","longitude" => "-61.5833"),
        'GQ' => array("CountryName" => "Equatorial Guinea","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "2.0000","longitude" => "10.0000"),
        'GR' => array("CountryName" => "Greece","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "39.0000","longitude" => "22.0000"),
        'GS' => array("CountryName" => "South Georgia and the South Sandwich Islands","ContinentIso" => "AN","ContinentName" => "Antarctica","latitude" => "-54.5000","longitude" => "-37.0000"),
        'GT' => array("CountryName" => "Guatemala","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "15.5000","longitude" => "-90.2500"),
        'GU' => array("CountryName" => "Guam","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "13.4667","longitude" => "144.7833"),
        'GW' => array("CountryName" => "Guinea-Bissau","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "12.0000","longitude" => "-15.0000"),
        'GY' => array("CountryName" => "Guyana","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "5.0000","longitude" => "-59.0000"),
        'HK' => array("CountryName" => "Hong Kong","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "22.2500","longitude" => "114.1667"),
        'HM' => array("CountryName" => "Heard Island and McDonald Islands","ContinentIso" => "AN","ContinentName" => "Antarctica","latitude" => "-53.1000","longitude" => "72.5167"),
        'HN' => array("CountryName" => "Honduras","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "15.0000","longitude" => "-86.5000"),
        'HR' => array("CountryName" => "Croatia","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "45.1667","longitude" => "15.5000"),
        'HT' => array("CountryName" => "Haiti","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "19.0000","longitude" => "-72.4167"),
        'HU' => array("CountryName" => "Hungary","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "47.0000","longitude" => "20.0000"),
        'ID' => array("CountryName" => "Indonesia","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "-5.0000","longitude" => "120.0000"),
        'IE' => array("CountryName" => "Ireland","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "53.0000","longitude" => "-8.0000"),
        'IL' => array("CountryName" => "Israel","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "31.5000","longitude" => "34.7500"),
        'IM' => array("CountryName" => "Isle of Man","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "54.2300","longitude" => "-4.5700"),
        'IN' => array("CountryName" => "India","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "20.0000","longitude" => "77.0000"),
        'IO' => array("CountryName" => "British Indian Ocean Territory","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "-6.0000","longitude" => "71.5000"),
        'IQ' => array("CountryName" => "Iraq","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "33.0000","longitude" => "44.0000"),
        'IR' => array("CountryName" => "Iran Islamic Republic of","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "32.0000","longitude" => "53.0000"),
        'IS' => array("CountryName" => "Iceland","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "65.0000","longitude" => "-18.0000"),
        'IT' => array("CountryName" => "Italy","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "42.8333","longitude" => "12.8333"),
        'JE' => array("CountryName" => "Jersey","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "49.2167","longitude" => "-2.1167"),
        'JM' => array("CountryName" => "Jamaica","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "18.2500","longitude" => "-77.5000"),
        'JO' => array("CountryName" => "Jordan","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "31.0000","longitude" => "36.0000"),
        'JP' => array("CountryName" => "Japan","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "36.0000","longitude" => "138.0000"),
        'KE' => array("CountryName" => "Kenya","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "1.0000","longitude" => "38.0000"),
        'KG' => array("CountryName" => "Kyrgyzstan","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "41.0000","longitude" => "75.0000"),
        'KH' => array("CountryName" => "Cambodia","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "13.0000","longitude" => "105.0000"),
        'KI' => array("CountryName" => "Kiribati","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "1.4167","longitude" => "173.0000"),
        'KM' => array("CountryName" => "Comoros","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-12.1667","longitude" => "44.2500"),
        'KN' => array("CountryName" => "Saint Kitts and Nevis","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "17.3333","longitude" => "-62.7500"),
        'KP' => array("CountryName" => "Korea Democratic People's Republic of","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "40.0000","longitude" => "127.0000"),
        'KR' => array("CountryName" => "Korea Republic of","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "37.0000","longitude" => "127.5000"),
        'KW' => array("CountryName" => "Kuwait","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "29.3375","longitude" => "47.6581"),
        'KY' => array("CountryName" => "Cayman Islands","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "19.5000","longitude" => "-80.5000"),
        'KZ' => array("CountryName" => "Kazakhstan","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "48.0000","longitude" => "68.0000"),
        'LA' => array("CountryName" => "Lao People's Democratic Republic","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "18.0000","longitude" => "105.0000"),
        'LB' => array("CountryName" => "Lebanon","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "33.8333","longitude" => "35.8333"),
        'LC' => array("CountryName" => "Saint Lucia","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "13.8833","longitude" => "-61.1333"),
        'LI' => array("CountryName" => "Liechtenstein","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "47.1667","longitude" => "9.5333"),
        'LK' => array("CountryName" => "Sri Lanka","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "7.0000","longitude" => "81.0000"),
        'LR' => array("CountryName" => "Liberia","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "6.5000","longitude" => "-9.5000"),
        'LS' => array("CountryName" => "Lesotho","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-29.5000","longitude" => "28.5000"),
        'LT' => array("CountryName" => "Lithuania","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "56.0000","longitude" => "24.0000"),
        'LU' => array("CountryName" => "Luxembourg","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "49.7500","longitude" => "6.1667"),
        'LV' => array("CountryName" => "Latvia","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "57.0000","longitude" => "25.0000"),
        'LY' => array("CountryName" => "Libyan Arab Jamahiriya","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "25.0000","longitude" => "17.0000"),
        'MA' => array("CountryName" => "Morocco","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "32.0000","longitude" => "-5.0000"),
        'MC' => array("CountryName" => "Monaco","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "43.7333","longitude" => "7.4000"),
        'MD' => array("CountryName" => "Moldova Republic of","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "47.0000","longitude" => "29.0000"),
        'ME' => array("CountryName" => "Montenegro","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "42.0000","longitude" => "19.0000"),
        'MF' => array("CountryName" => "Saint Martin","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "18.0500","longitude" => "-63.0800"),
        'MG' => array("CountryName" => "Madagascar","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-20.0000","longitude" => "47.0000"),
        'MH' => array("CountryName" => "Marshall Islands","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "9.0000","longitude" => "168.0000"),
        'MK' => array("CountryName" => "Macedonia","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "41.8333","longitude" => "22.0000"),
        'ML' => array("CountryName" => "Mali","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "17.0000","longitude" => "-4.0000"),
        'MM' => array("CountryName" => "Myanmar","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "22.0000","longitude" => "98.0000"),
        'MN' => array("CountryName" => "Mongolia","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "46.0000","longitude" => "105.0000"),
        'MO' => array("CountryName" => "Macao","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "22.1667","longitude" => "113.5500"),
        'MP' => array("CountryName" => "Northern Mariana Islands","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "15.2000","longitude" => "145.7500"),
        'MQ' => array("CountryName" => "Martinique","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "14.6667","longitude" => "-61.0000"),
        'MR' => array("CountryName" => "Mauritania","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "20.0000","longitude" => "-12.0000"),
        'MS' => array("CountryName" => "Montserrat","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "16.7500","longitude" => "-62.2000"),
        'MT' => array("CountryName" => "Malta","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "35.8333","longitude" => "14.5833"),
        'MU' => array("CountryName" => "Mauritius","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-20.2833","longitude" => "57.5500"),
        'MV' => array("CountryName" => "Maldives","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "3.2500","longitude" => "73.0000"),
        'MW' => array("CountryName" => "Malawi","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-13.5000","longitude" => "34.0000"),
        'MX' => array("CountryName" => "Mexico","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "23.0000","longitude" => "-102.0000"),
        'MY' => array("CountryName" => "Malaysia","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "2.5000","longitude" => "112.5000"),
        'MZ' => array("CountryName" => "Mozambique","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-18.2500<35.0000","longitude" => ""),
        'NA' => array("CountryName" => "Namibia","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-22.0000","longitude" => "17.0000"),
        'NC' => array("CountryName" => "New Caledonia","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-21.5000","longitude" => "165.5000"),
        'NE' => array("CountryName" => "Niger","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "16.0000","longitude" => "8.0000"),
        'NF' => array("CountryName" => "Norfolk Island","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-29.0333","longitude" => "167.9500"),
        'NG' => array("CountryName" => "Nigeria","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "10.0000","longitude" => "8.0000"),
        'NI' => array("CountryName" => "Nicaragua","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "13.0000","longitude" => "-85.0000"),
        'NL' => array("CountryName" => "Netherlands","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "52.5000","longitude" => "5.7500"),
        'NO' => array("CountryName" => "Norway","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "62.0000","longitude" => "10.0000"),
        'NP' => array("CountryName" => "Nepal","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "28.0000","longitude" => "84.0000"),
        'NR' => array("CountryName" => "Nauru","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-0.5333","longitude" => "166.9167"),
        'NU' => array("CountryName" => "Niue","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-19.0333","longitude" => "-169.8667"),
        'NZ' => array("CountryName" => "New Zealand","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-41.0000","longitude" => "174.0000"),
        'O1' => array("CountryName" => "Other Country","ContinentIso" => "--","ContinentName" => "--","latitude" => "--","longitude" => "--"),
        'OM' => array("CountryName" => "Oman","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "21.0000","longitude" => "57.0000"),
        'PA' => array("CountryName" => "Panama","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "9.0000","longitude" => "-80.0000"),
        'PE' => array("CountryName" => "Peru","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "-10.0000","longitude" => "-76.0000"),
        'PF' => array("CountryName" => "French Polynesia","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-15.0000","longitude" => "-140.0000"),
        'PG' => array("CountryName" => "Papua New Guinea","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-6.0000","longitude" => "147.0000"),
        'PH' => array("CountryName" => "Philippines","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "13.0000","longitude" => "122.0000"),
        'PK' => array("CountryName" => "Pakistan","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "30.0000","longitude" => "70.0000"),
        'PL' => array("CountryName" => "Poland","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "52.0000","longitude" => "20.0000"),
        'PM' => array("CountryName" => "Saint Pierre and Miquelon","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "46.8333","longitude" => "-56.3333"),
        'PN' => array("CountryName" => "Pitcairn","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-24.36146","longitude" => "-128.316376"),
        'PR' => array("CountryName" => "Puerto Rico","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "18.2500","longitude" => "-66.5000"),
        'PS' => array("CountryName" => "Palestinian Territory","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "32.0000","longitude" => "35.2500"),
        'PT' => array("CountryName" => "Portugal","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "39.5000","longitude" => "-8.0000"),
        'PW' => array("CountryName" => "Palau","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "7.5000","longitude" => "134.5000"),
        'PY' => array("CountryName" => "Paraguay","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "-23.0000","longitude" => "-58.0000"),
        'QA' => array("CountryName" => "Qatar","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "25.5000","longitude" => "51.2500"),
        'RE' => array("CountryName" => "Reunion","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-21.1000","longitude" => "55.6000"),
        'RO' => array("CountryName" => "Romania","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "46.0000","longitude" => "25.0000"),
        'RS' => array("CountryName" => "Serbia","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "44.0000","longitude" => "21.0000"),
        'RU' => array("CountryName" => "Russian Federation","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "60.0000","longitude" => "100.0000"),
        'RW' => array("CountryName" => "Rwanda","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-2.0000","longitude" => "30.0000"),
        'SA' => array("CountryName" => "Saudi Arabia","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "25.0000","longitude" => "45.0000"),
        'SB' => array("CountryName" => "Solomon Islands","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-8.0000","longitude" => "159.0000"),
        'SC' => array("CountryName" => "Seychelles","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-4.5833","longitude" => "55.6667"),
        'SD' => array("CountryName" => "Sudan","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "15.0000","longitude" => "30.0000"),
        'SE' => array("CountryName" => "Sweden","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "62.0000","longitude" => "15.0000"),
        'SG' => array("CountryName" => "Singapore","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "1.3667","longitude" => "103.8000"),
        'SH' => array("CountryName" => "Saint Helena","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-15.9333","longitude" => "-5.7000"),
        'SI' => array("CountryName" => "Slovenia","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "46.0000","longitude" => "15.0000"),
        'SJ' => array("CountryName" => "Svalbard and Jan Mayen","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "78.0000","longitude" => "20.0000"),
        'SK' => array("CountryName" => "Slovakia","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "48.6667","longitude" => "19.5000"),
        'SL' => array("CountryName" => "Sierra Leone","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "8.5000","longitude" => "-11.5000"),
        'SM' => array("CountryName" => "San Marino","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "43.7667","longitude" => "12.4167"),
        'SN' => array("CountryName" => "Senegal","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "14.0000","longitude" => "-14.0000"),
        'SO' => array("CountryName" => "Somalia","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "10.0000","longitude" => "49.0000"),
        'SR' => array("CountryName" => "Suriname","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "4.0000","longitude" => "-56.0000"),
        'ST' => array("CountryName" => "Sao Tome and Principe","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "1.0000","longitude" => "7.0000"),
        'SV' => array("CountryName" => "El Salvador","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "13.8333","longitude" => "-88.9167"),
        'SY' => array("CountryName" => "Syrian Arab Republic","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "35.0000","longitude" => "38.0000"),
        'SZ' => array("CountryName" => "Swaziland","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-26.5000","longitude" => "31.5000"),
        'TC' => array("CountryName" => "Turks and Caicos Islands","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "21.7500","longitude" => "-71.5833"),
        'TD' => array("CountryName" => "Chad","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "15.0000","longitude" => "19.0000"),
        'TF' => array("CountryName" => "French Southern Territories","ContinentIso" => "AN","ContinentName" => "Antarctica","latitude" => "-43.0000","longitude" => "67.0000"),
        'TG' => array("CountryName" => "Togo","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "8.0000","longitude" => "1.1667"),
        'TH' => array("CountryName" => "Thailand","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "15.0000","longitude" => "100.0000"),
        'TJ' => array("CountryName" => "Tajikistan","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "39.0000","longitude" => "71.0000"),
        'TK' => array("CountryName" => "Tokelau","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-9.0000","longitude" => "-172.0000"),
        'TL' => array("CountryName" => "Timor-Leste","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "-8.5000","longitude" => "125.5500"),
        'TM' => array("CountryName" => "Turkmenistan","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "40.0000","longitude" => "60.0000"),
        'TN' => array("CountryName" => "Tunisia","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "34.0000","longitude" => "9.0000"),
        'TO' => array("CountryName" => "Tonga","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-20.0000","longitude" => "-175.0000"),
        'TR' => array("CountryName" => "Turkey","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "39.0000","longitude" => "35.0000"),
        'TT' => array("CountryName" => "Trinidad and Tobago","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "11.0000","longitude" => "-61.0000"),
        'TV' => array("CountryName" => "Tuvalu","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-8.0000","longitude" => "178.0000"),
        'TW' => array("CountryName" => "Taiwan","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "23.5000","longitude" => "121.0000"),
        'TZ' => array("CountryName" => "Tanzania United Republic of","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-6.0000","longitude" => "35.0000"),
        'UA' => array("CountryName" => "Ukraine","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "49.0000","longitude" => "32.0000"),
        'UG' => array("CountryName" => "Uganda","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "1.0000","longitude" => "32.0000"),
        'UM' => array("CountryName" => "United States Minor Outlying Islands","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "19.2833","longitude" => "166.6000"),
        'US' => array("CountryName" => "United States","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "38.0000","longitude" => "-97.0000"),
        'UY' => array("CountryName" => "Uruguay","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "-33.0000","longitude" => "-56.0000"),
        'UZ' => array("CountryName" => "Uzbekistan","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "41.0000","longitude" => "64.0000"),
        'VA' => array("CountryName" => "Holy See (Vatican City State)","ContinentIso" => "EU","ContinentName" => "Europe","latitude" => "41.9000","longitude" => "12.4500"),
        'VC' => array("CountryName" => "Saint Vincent and the Grenadines","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "13.2500","longitude" => "-61.2000"),
        'VE' => array("CountryName" => "Venezuela","ContinentIso" => "SA","ContinentName" => "South America","latitude" => "8.0000","longitude" => "-66.0000"),
        'VG' => array("CountryName" => "Virgin Islands British","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "18.5000","longitude" => "-64.5000"),
        'VI' => array("CountryName" => "Virgin Islands U.S.","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "18.3333","longitude" => "-64.8333"),
        'VN' => array("CountryName" => "Vietnam","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "16.0000","longitude" => "106.0000"),
        'VU' => array("CountryName" => "Vanuatu","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-16.0000","longitude" => "167.0000"),
        'WF' => array("CountryName" => "Wallis and Futuna","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-13.3000","longitude" => "-176.2000"),
        'WS' => array("CountryName" => "Samoa","ContinentIso" => "OC","ContinentName" => "Australia (Oceania)","latitude" => "-13.5833","longitude" => "-172.3333"),
        'YE' => array("CountryName" => "Yemen","ContinentIso" => "AS","ContinentName" => "Asia","latitude" => "15.0000","longitude" => "48.0000"),
        'YT' => array("CountryName" => "Mayotte","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-12.8333","longitude" => "45.1667"),
        'ZA' => array("CountryName" => "South Africa","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-29.0000","longitude" => "24.0000"),
        'ZM' => array("CountryName" => "Zambia","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-15.0000","longitude" => "30.0000"),
        'ZW' => array("CountryName" => "Zimbabwe","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "-20.0000","longitude" => "30.0000"),
        'BQ' => array("CountryName" => "Bonaire Saint Eustatius and Saba","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "17.4826800","longitude" => "-62.9832400"),
        'CW' => array("CountryName" => "Curacao","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "12.1166666667","longitude" => "-68.9333333333"),
        'SS' => array("CountryName" => "South Sudan","ContinentIso" => "AF","ContinentName" => "Africa","latitude" => "4.8500","longitude" => "31.6000"),
        'SX' => array("CountryName" => "Sint Maarten","ContinentIso" => "NA","ContinentName" => "North America","latitude" => "18.0500","longitude" => "-63.0800")
    );

    protected static function set(){
        //$sMaxmindServiceClasses = TRAWLER_PATH_LIBRARIES . '/Vendors/Maxmind/GeoIP/src/geoip.inc';

        //if(file_exists($sMaxmindServiceClasses)){
            //include($sMaxmindServiceClasses);

            self::getMaxmindCountry();
            self::getMaxmindASN();
        //}
    }

    public static function getVisitorCountryCode(){
        if(!isset(SpamTrawler::$Registry['visitordetails']['country_code'])){
            self::set();
        }
        return SpamTrawler::$Registry['visitordetails']['country_code'];
    }

    public static function getVisitorCountryName(){
        if(!isset(SpamTrawler::$Registry['visitordetails']['country_name'])){
            self::set();
        }
        return SpamTrawler::$Registry['visitordetails']['country_name'];
    }

    public static function getVisitorContinentCode(){
        if(!isset(SpamTrawler::$Registry['visitordetails']['continent_iso'])){
            self::set();
        }
        return SpamTrawler::$Registry['visitordetails']['continent_iso'];
    }

    public static function getVisitorContinentName(){
        if(!isset(SpamTrawler::$Registry['visitordetails']['continent_name'])){
            self::set();
        }
        return SpamTrawler::$Registry['visitordetails']['continent_name'];
    }

    public static function getVisitorLatitude(){
        if(!isset(SpamTrawler::$Registry['visitordetails']['latitude'])){
            self::set();
        }
        return SpamTrawler::$Registry['visitordetails']['latitude'];
    }

    public static function getVisitorLongitude(){
        if(!isset(SpamTrawler::$Registry['visitordetails']['longitude'])){
            self::set();
        }
        return SpamTrawler::$Registry['visitordetails']['longitude'];
    }

    public static function getVisitorASN(){
        if(!isset(SpamTrawler::$Registry['visitordetails']['asn'])){
            self::set();
        }
        return SpamTrawler::$Registry['visitordetails']['asn'];
    }

    public static function getVisitorASNOrg(){
        if(!isset(SpamTrawler::$Registry['visitordetails']['asn_org'])){
            self::set();
        }
        return SpamTrawler::$Registry['visitordetails']['asn_org'];
    }

    public static function getMaxmindCountry(){
        if(!isset(SpamTrawler::$Registry['visitordetails']['country_code'])){
            if(!SpamTrawler_VisitorDetails_IP_IP::isV6()){
                $sDbPath = TRAWLER_PATH_FILES . '/GeoIP/Maxmind/GeoIP.dat';
            } else {
                $sDbPath = TRAWLER_PATH_FILES . '/GeoIP/Maxmind/GeoIPv6.dat';
            }

            if(file_exists($sDbPath)){
                $gicountry = geoip_open($sDbPath, GEOIP_STANDARD);

                if(!SpamTrawler_VisitorDetails_IP_IP::isV6()){
                    $country = geoip_country_code_by_addr($gicountry, SpamTrawler_VisitorDetails_IP_IP::get());
                } else {
                    $country = geoip_country_code_by_addr_v6($gicountry, SpamTrawler_VisitorDetails_IP_IP::get());
                }

                geoip_close($gicountry);

                if(!empty($country)){
                    //Get countryname and Continent name from countrycode
                    $aCountryInfo = self::$aCountryInfo[$country];

                    //Set GeoIP details
                    SpamTrawler::$Registry['visitordetails']['country_code'] = $country;
                    SpamTrawler::$Registry['visitordetails']['country_name'] =  $aCountryInfo['CountryName'];
                    SpamTrawler::$Registry['visitordetails']['continent_iso'] =  $aCountryInfo['ContinentIso'];
                    SpamTrawler::$Registry['visitordetails']['continent_name'] = $aCountryInfo['ContinentName'];
                    SpamTrawler::$Registry['visitordetails']['latitude'] = $aCountryInfo['latitude'];
                    SpamTrawler::$Registry['visitordetails']['longitude'] = $aCountryInfo['longitude'];
                } else {
                    $country = '--';
                    //Set GeoIP details
                    SpamTrawler::$Registry['visitordetails']['country_code'] = $country;
                    SpamTrawler::$Registry['visitordetails']['country_name'] =  '--';
                    SpamTrawler::$Registry['visitordetails']['continent_iso'] =  '--';
                    SpamTrawler::$Registry['visitordetails']['continent_name'] = '--';
                    SpamTrawler::$Registry['visitordetails']['latitude'] = '--';
                    SpamTrawler::$Registry['visitordetails']['longitude'] = '--';
                }



                //SpamTrawler::$Registry->visitordetails->continent_name = geoip_continent_code_by_name($gicountry, $countryName); //FixMe
                /*
                SpamTrawler::$Registry->visitordetails->latitude = $gicountry->GEOIP_COUNTRY_CONTINENT[$countryName]['latitude'];
                SpamTrawler::$Registry->visitordetails->longitude = $gicountry->GEOIP_COUNTRY_CONTINENT[$countryName]['longitude'];
                SpamTrawler::$Registry->visitordetails->continent_name = $gicountry->GEOIP_COUNTRY_CONTINENT[$country]['ContinentName'];
                SpamTrawler::$Registry->visitordetails->continent_iso = $gicountry->GEOIP_COUNTRY_CONTINENT[$country]['ContinentIso'];
                SpamTrawler::$Registry->visitordetails->latitude = $gicountry->GEOIP_COUNTRY_CONTINENT[$country]['latitude'];
                SpamTrawler::$Registry->visitordetails->longitude = $gicountry->GEOIP_COUNTRY_CONTINENT[$country]['longitude'];
                */

                return $country;
            }

                return '--';
        }
        return SpamTrawler::$Registry['visitordetails']['country_code'];
    }

    public static function getMaxmindASN(){
        if(!isset(SpamTrawler::$Registry['visitordetails']['asn'])){
        if(!SpamTrawler_VisitorDetails_IP_IP::isV6()){
            $sDbPath = TRAWLER_PATH_FILES . '/GeoIP/Maxmind/GeoIPASNum.dat';
        } else {
            $sDbPath = TRAWLER_PATH_FILES . '/GeoIP/Maxmind/GeoIPASNumv6.dat';
        }

        if(file_exists($sDbPath)){
            $giasn = geoip_open($sDbPath, GEOIP_STANDARD);

            if(!SpamTrawler_VisitorDetails_IP_IP::isV6()){
                $sAsn = geoip_name_by_addr($giasn, SpamTrawler_VisitorDetails_IP_IP::get());
            } else {
                $sAsn = geoip_name_by_addr_v6($giasn, SpamTrawler_VisitorDetails_IP_IP::get());
            }

            geoip_close($giasn);

            //Set ASN details
            $aAsnParts = explode(" ", $sAsn);
            SpamTrawler::$Registry['visitordetails']['asn'] = $aAsnParts['0'];

            //Remove ASN number from array
            unset($aAsnParts['0']);

            SpamTrawler::$Registry['visitordetails']['asn_org'] = implode(" ", $aAsnParts);


            return $sAsn;
        }
            return '--';
        }
        return SpamTrawler::$Registry['visitordetails']['asn'];
    }
} 