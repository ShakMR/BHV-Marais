<?php
/**
 * Created by PhpStorm.
 * User: shakmr
 * Date: 15/11/15
 * Time: 11:47
 */

require_once("../Core/DBHelper.php");
require_once("../utils.php");

class CodeDispenser extends DBHelper {

    private $codes = array();
    private $SQL = "SELECT code FROM Code;";
    private $i;

    function CodeDispenser() {
        DBHelper::connect();
        DBHelper::executeStatement($this->SQL);
        $this->codes = array_map(function ($x) {return $x[0];}, DBHelper::$result);
        shuffle($this->codes);
        $this->i=0;
    }

    function get_random_code() {
        $code = $this->codes[$this->i];
        $this->i++;
        return $code;
    }

}