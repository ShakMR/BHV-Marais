<?php

/**
 * Created by PhpStorm.
 * User: shakmr
 * Date: 11/11/15
 * Time: 18:44
 */
require_once("../Core/StringDispenser.php");

class StringDispenserTest extends PHPUnit_Framework_TestCase
{
    public function testget_user_already_registered_string() {
        $strdisp = new StringDispenser();
        $this->assertTrue($strdisp->get_user_already_registered_string() == "The user is already registered in the system");
    }

    public function testget_code_used_string() {
        $strdisp = new StringDispenser();
        $this->assertTrue($strdisp->get_code_used_string() == "The code inserted is already used");
    }

    public function testget_code_invalid_string() {
        $strdisp = new StringDispenser();
        $this->assertTrue($strdisp->get_code_invalid_string() == "The code inserted is not valid");
    }
}
