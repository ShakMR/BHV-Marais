<?php

/**
 * Created by PhpStorm.
 * User: shakmr
 * Date: 11/11/15
 * Time: 0:22
 */

require_once("../Core/BhvDB.php");

class Inscription {
    public $name;
    public $lastname;
    public $email;
    public $code;

    public function __construct($name, $lastname, $email, $code)
    {
        $this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->code = $code;
    }
}

echo "Preparing database for unittest\n";
//BhvDB::restore_dump("bhv_dev-dump.sql");
echo "DONE\nStarting UnitTest...\n";


class BhvDBTest extends PHPUnit_Framework_TestCase
{

    public function testNewInscriptionAwarded()
    {
//        BhvDB::clear_inscription();
//        BhvDB::clear_people();
        $ins = new Inscription("name1", "lastname1", "email2", "A000001");
        try {
            $ret = BhvDB::new_inscription($ins->name, $ins->lastname, $ins->email, DBHelper::today(), $ins->code);
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->fail();
        }

    }

    public function testNewInscriptionNotAwarded()
    {
//        BhvDB::clear_inscription();
//        BhvDB::clear_people();
        $ins = new Inscription("name2", "lastname2", "email2", "6P3S5");
        $ret = BhvDB::new_inscription($ins->name, $ins->lastname, $ins->email, DBHelper::today(), $ins->code);

        $this->assertTrue(!$ret);
    }


    public function testExistingPerson()
    {
//        BhvDB::clear_inscription();
//        BhvDB::clear_people();
        $ins = new Inscription("name3", "lastname3", "email3", "7F65V");
        $ret = BhvDB::new_inscription($ins->name, $ins->lastname, $ins->email, DBHelper::today(), $ins->code);

        $ins2 = new Inscription("name4", "lastname4", "email3", "C5VVU");
        $error = false;
        try {
            $ret = BhvDB::new_inscription($ins2->name, $ins2->lastname, $ins2->email, DBHelper::today(), $ins2->code);
        } catch (Exception $e) {
//            echo $e->getMessage();
            $this->assertTrue($e->getMessage() == "The user is already registered in the system");
            $error = true;
        }
        if (!$error)
            $this->fail();
    }


    public function testAuthenticationOK() {
        $username = "bhv-marais";
        $password = "dwph4DVq";
        $this->assertTrue(BhvDB::authenticate($username, $password));
    }
}
