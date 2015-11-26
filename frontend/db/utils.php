<?php
/**
 * Created by PhpStorm.
 * User: shakmr
 * Date: 15/11/15
 * Time: 18:28
 */

function get_float_rand($min=null, $max=null) {
    if (is_null($min) && is_null($max)) {
        return mt_rand() / mt_getrandmax();
    }
    else {
        return mt_rand($min, $max) / mt_getrandmax();
    }
}