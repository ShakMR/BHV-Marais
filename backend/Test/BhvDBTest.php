<?php

/**
 * Created by PhpStorm.
 * User: shakmr
 * Date: 11/11/15
 * Time: 0:22
 */
class BhvDBTest extends PHPUnit_Framework_TestCase
{
    const name="name1";
    const lastname = "lastname1";
    const email="email1";
    const code="00021";

    public function testNewInscription()
    {
        $ret = BhvDB::new_inscription(self::name, self::lastname, self::email, DBHelper::today(), self::code);

        $this->assertTrue($ret>0);
    }
}
