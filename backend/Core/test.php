<?php
/**
 * Created by PhpStorm.
 * User: shakmr
 * Date: 11/11/15
 * Time: 0:50
 */

require_once("BhvDB.php");

class Test
{
    const name="name1";
    const lastname = "lastname1";
    const email="email1";
    const code="00021";

    public function test()
    {
        try {
            $ret = BhvDB::new_inscription(self::name, self::lastname, self::email, DBHelper::today(), self::code);
        }
        catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }

        echo $ret;
    }

}

$t = new Test();