<?php

/**
 * Created by PhpStorm.
 * User: borja
 * Date: 9/11/15
 * Time: 23:11
 */
class StringDispenser
{
    private static $error_file = "../Strings/error.ini";
    private static $strings;

    const USER_ALREADY_REGISTERED = "user_already_registered";
    const CODE_USED = "code_already_used";
    const CODE_INVALID = "code_not_valid";
    const AUTH_FAIL = "authentication_not_valid";

    private static function load_strings() {
        $error_file_parsed = parse_ini_file(self::$error_file, true);
        $lang = $error_file_parsed['lang']['lang'];
        self::$strings = $error_file_parsed[$lang];
    }

    public static function get_message($key) {
        self::load_strings();
        return self::$strings[$key];
    }

    public static function get_user_already_registered_string() {
        return self::get_message(self::USER_ALREADY_REGISTERED);
    }

    public static function get_code_used_string() {
        return self::get_message(self::CODE_USED);
    }

    public static function get_code_invalid_string() {
        return self::get_message(self::CODE_INVALID);
    }

    public static function get_auth_fail_string() {
        return self::get_message(self::AUTH_FAIL);
    }
}