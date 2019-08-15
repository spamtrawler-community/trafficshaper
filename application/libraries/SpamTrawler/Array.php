<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 04/02/15
 * Time: 18:58
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler) 
 */

class SpamTrawler_Array {
    //flattens multidimensional arrays
    public static function flatten($array, $newArray = Array() ,$prefix='',$delimiter='|') {
        foreach ($array as $key => $child) {
            if (is_array($child)) {
                $newPrefix = $prefix.$key.$delimiter;
                $newArray = self::flatten($child, $newArray ,$newPrefix, $delimiter);
            } else {
                $newArray[$prefix.$key] = $child;
            }
        }
        return $newArray;
    }
}