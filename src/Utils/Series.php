<?php


namespace SuperAdmin\Utils;

use DateTimeImmutable;

/**
 * Class Series
 * @package SuperAdmin\Utils
 */
class Series {

    /**
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
    public static function sum(array $arr1, array $arr2){
        $result = [];
        foreach ($arr1 as $key => $value){
            $result[$key] = $arr1[$key] + $arr2[$key];
        }
        return $result;
    }


    /**
     * @param array $arr1
     * @param float $rate
     * @return array
     */
    public static function mul(array $arr1, float $rate) {
        $result = [];
        foreach ($arr1 as $key => $value){
            $result[$key] = $arr1[$key] * $rate;
        }
        return $result;
    }

    /**
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
    public static function sub(array $arr1, array $arr2){
        return self::sum($arr1, self::mul($arr2, -1));
    }
}