<?php
/**
 * Created by PhpStorm.
 * User: shakmr
 * Date: 15/11/15
 * Time: 11:46
 */

require_once("CodeDispenser.php");
require_once("../utils.php");

class Agent {

    public $name;
    public $last_name;
    public $email;
    public $code;
    public $probability;

    function Agent($name, $last_name, $prob, CodeDispenser &$dispenser) {
        $this->name=$name;
        $this->last_name = $last_name;
        $this->email = $name.".".$last_name."@".$name.".com";
        $this->code = $dispenser->get_random_code();
        $this->probability = $prob;
    }

    function wants_to_play() {
        $r = get_float_rand();
        return $r < $this->probability;
    }

    function play() {
        return array($this->name, $this->last_name, $this->email, $this->code);
    }
}