<?php

/*
 * Created by PhpStorm.
 * User: borja
 * Date: 9/11/15
 * Time: 22:14
 */
require_once("DBHelper.php");
require_once("StringDispenser.php");

class BhvDB extends DBHelper
{
    private static $remaining_awards_sql =     "SELECT * FROM Awards WHERE not delivered and activation_date <= :_1;";
    private static $search_code_sql =          "SELECT * FROM Code c LEFT JOIN Inscription i ON i.Code_idCode = c.idCode WHERE idInscription is NULL and code = :_1;";
    private static $search_person_sql =        "SELECT * FROM People WHERE email=:_1;";

    private static $insert_new_person_sql =    "INSERT INTO People (name, lastname, email) VALUES (?, ?, ?);";
    private static $insert_inscription_sql =   "INSERT INTO Inscription (People_idPerson, Code_idCode, Awards_idAward, date) VALUES(?, ?, ?, ?);";

    private static $modify_award_sql =         "UPDATE Awards SET delivered=:_1 WHERE idAwards=:_2;";

    private static $authenticate_sql =         "SELECT COUNT(*) FROM access WHERE username=:_1 AND password=PASSWORD(:_2);";

    const People_id = "idPerson";
    const Code_id   = "idCode";
    const Award_id  = "idAward";
    const Inscription_id = "idInscription";

    private static function executeTransactionStatement ($statement) {
        try {
            $stmt = self::$db->prepare($statement->get_SQL());
            $params = $statement->get_params();
            $stmt->execute($params);
        }
        catch (Exception $exception) {
            self::$db->rollBack();
            self::$db->close();
            throw $exception;
        }
    }

    public static function register ($name, $lastname, $email, $idCode, $idAward, $date)
    {
        self::executeStatement(self::$search_person_sql, $email);
        if (count(self::$result) >= 1)
            throw new Exception(StringDispenser::get_user_already_registered_string());
        else {
            // if the user doesn't exists => create the user
            //
//            self::executeStatement(self::$insert_new_person_sql, $name, $lastname, $email);
//            return self::$result[0][self::People_id];
            $stmt1 = new StatementHelper(self::$insert_new_person_sql);
            $stmt1->add_parameter($name);
            $stmt1->add_parameter($lastname);
            $stmt1->add_parameter($email);
            self::$db->beginTransaction();

            self::executeTransactionStatement($stmt1);

            $pers_id = self::$db->lastInsertId();
            $stmt2 = new StatementHelper(self::$insert_inscription_sql);
            $stmt2->add_parameter($pers_id);
            $stmt2->add_parameter($idCode);
            $stmt2->add_parameter($idAward);
            $stmt2->add_parameter($date);

            self::executeTransactionStatement($stmt2);

            self::$db->commit ();
        }
    }

    public static function check_awards ($date)
        /**
         * Returns the award id if is awarded, return null otherwise.
         */
    {
        self::executeStatement(self::$remaining_awards_sql, $date);
        $n = count(self::$result);
        if ($n >= 1) {
            return self::$result[0][self::Award_id];
        }
        return null;
    }

    public static function get_valid_code ($code)
    {
        self::executeStatement(self::$search_code_sql, $code);
        $n = count(self::$result);
        if ($n == 1)
        {
            return self::$result[0][self::Code_id];
        }
        throw new Exception(StringDispenser::get_code_invalid_string());
    }

    public static function new_inscription($name, $lastname, $email, $date, $code)
    {
        self::connect();
        $idCode = self::get_valid_code($code);
        $idAwars = self::check_awards($date);
        self::register($name, $lastname, $email, $idCode, $idAwars, $date);
        $id = self::$db->lastInsertId();
        self::close();
        return $id;
    }
}