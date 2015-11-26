<?php

/*
 * Created by PhpStorm.
 * User: borja
 * Date: 9/11/15
 * Time: 22:14
 */
require_once("DBHelper.php");
require_once("StringDispenser.php");
require_once("Mailer.php");
require_once(realpath(dirname(__FILE__))."/../utils.php");


/**
 * Class BhvDB
 * This class is used as a adapter to acces de database using the minimum function
 * Use the functions new_inscription(), authenticate() and check_session()
 * @author Borja Arias
 */
class BhvDB extends DBHelper
{
    /**
     * @var string
     * SQL query to fetch Awards that must be activated but not yet delivered
     * @ignore
     */
    private static $remaining_awards_sql =     "SELECT * FROM Awards WHERE not delivered and activation_date <= :_1;";
    /**
     * @var string SQL query to fetch an unused code by the key
     */
    private static $search_code_sql =          "SELECT * FROM Code c LEFT JOIN Inscription i ON i.Code_idCode = c.idCode WHERE idInscription is NULL and code = :_1;";
    /**
     * @var string SQL to search person by the email
     */
    private static $search_person_sql =        "SELECT * FROM People WHERE email=:_1;";

    /**
     * @var string SQL query to inster a new person in the database
     */
    private static $insert_new_person_sql =    "INSERT INTO People (name, lastname, email) VALUES (?, ?, ?);";
    /**
     * @var string SQL query to insert a new inscription in the database
     */
    private static $insert_inscription_sql =   "INSERT INTO Inscription (People_idPerson, Code_idCode, Awards_idAward, date) VALUES(?, ?, ?, ?);";

    /**
     * @var string SQL query to mark an Awards as delivered
     */
    private static $modify_award_sql =         "UPDATE Awards SET delivered=true WHERE idAward=:_1;";

    /**
     * @var string SQL query to increase an Award's probability
     */
    private static $increase_award_sql =         "UPDATE Awards SET probability=:_2 WHERE idAward=:_1;";

    /**
     * @var string SQL query to check for a matching username and password
     */
    private static $authenticate_sql =         "SELECT COUNT(*) FROM access WHERE username=:_1 AND password=PASSWORD(:_2);";


    /**
     * username for session cookie
     */
    const username = 'bhv-marais';
    /**
     * id column name for person
     */
    const People_id = "idPerson";
    /**
     * id column name for code
     */
    const Code_id   = "idCode";
    /**
     * id column name for awards
     */
    const Award_id  = "idAward";
    const Award_prob= "probability";
    /**
     * id column name for inscriptions
     */
    const Inscription_id = "idInscription";

    /**
     * Executes an SQL query with or without params but do not commits the transaction
     * @param StatementHelper $statement
     * @throws Exception MYSQL Exception
     */
    private static function executeTransactionStatement ($statement) {
        try {
            $stmt = self::$db->prepare($statement->get_SQL());
            $params = $statement->get_params();
            $stmt->execute($params);
        }
        catch (Exception $exception) {
            self::$db->rollBack();
            self::close();
            throw $exception;
        }
    }

    /**
     * This function checks if a person identified by the email already exists in the database. If not starts a transaction
     * creating a new registry in the people table and another in the inscription table.
     * @param $name string Name of the person
     * @param $lastname string Family name of the person
     * @param $email string Email of the person
     * @param $idCode int Code id
     * @param $idAward int Award id
     * @param $date DateTime inscription date
     * @throws Exception Throws and exception if the user is already in the database
     */
    private static function register ($name, $lastname, $email, $idCode, $idAward, $date)
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

    /**
     * Check if the inscription is awarded or not.
     * @param $date DateTime inscription's date
     * @return int If its awarded, null otherwise
     * @throws Exception MYSQL Exception
     */
    private static function check_awards ($date)
        /**
         * Returns the award id if is awarded, return null otherwise.
         */
    {
        self::executeStatement(self::$remaining_awards_sql, $date);
        $n = count(self::$result);
        if ($n >= 1) {
            $r = get_float_rand();
            if ($r < self::$result[0][self::Award_prob]) {
                return self::$result[0][self::Award_id];
            }
            else {
                self::executeStatement(self::$increase_award_sql, self::$result[0][self::Award_id], self::$result[0][self::Award_prob]+0.15);
            }
        }
        return null;
    }

    /**
     * Check if the code is valid. A valid code is that one that is not used and exists in the database
     * @param $code string code key
     * @return int code identificator if found
     * @throws Exception Invalid code Exception
     */
    private static function get_valid_code ($code)
    {
        self::executeStatement(self::$search_code_sql, $code);
        $n = count(self::$result);
        if ($n == 1)
        {
            return self::$result[0][self::Code_id];
        }
        throw new Exception(StringDispenser::get_code_invalid_string());
    }

    /**
     * Changes the awards delivered state in the database to true.
     * @param $idAward int award id that must be changed
     * @throws Exception MYSQL Exception
     */
    private static function deliver_award($idAward)
    {
        self::connect();
        self::executeStatement(self::$modify_award_sql, $idAward);
        self::close();
    }

    /**
     * This function check if the code is valid, if it's awarded, register the inscription and change the award state if
     * needed.
     * @param $name string Name of the person
     * @param $lastname string Family name of the person
     * @param $email string Email of the person
     * @param $date DateTime inscription's date
     * @param $code string code key
     * @return bool true if the inscription is awarded, false otherwise
     * @throws Exception Invalid Code Exception, User Already Registered Exception
     * @see get_valid_code()
     */
    public static function new_inscription($name, $lastname, $email, $date, $code)
    {
        self::connect();
        $idCode = self::get_valid_code($code);
        $idAwards = self::check_awards($date);
        self::register($name, $lastname, $email, $idCode, $idAwards, $date);
        $m = new Mailer(new MailParticipation());
        $m->bindParams([
            "name"=>$name,
            "lastname"=>$lastname,
            "email"=>$email,
            "date"=>$date,
            "code"=>$code
            ]);
        $m->sendMail();
//        $id = self::$db->lastInsertId();
        if (!is_null($idAwards)) {
            self::deliver_award($idAwards);
            $wm = new Mailer(new MailWinner());
            $wm->sendMail();
        }
        self::close();
        return !is_null($idAwards);
    }

    /**
     *
     */
    public static function generate_session()
    {
        session_start();
        $_SESSION['user'] = self::username;
        $_SESSION['timestamp'] = time();
        session_commit();
    }

    /**
     * Authenticate the user in the system
     * @param $username string
     * @param $password string without encoding
     * @return bool true if a matching username and password found
     * @throws Exception Authentication Fail Exception
     */
    public static function authenticate($username, $password)
    {
        self::connect();
        self::executeStatement(self::$authenticate_sql, $username, $password);
        self::close();
        if (self::$result[0][0] == 1)
        {
            self::generate_session();
            return true;
        }
        throw new Exception(StringDispenser::get_auth_fail_string());
    }

    /**
     * Checks if the session exist and is still valid.
     * @return bool True if the session exist and is valid, false otherwise
     */
    public static function check_session()
    {
        session_start();
        $one_hour_before = time() - 3600;
        if (isset($_SESSION['user']) && $_SESSION['user'] == self::username && $_SESSION['timestamp'] >= $one_hour_before)
        {
            //timestamp renew
            $_SESSION['timestamp'] = time();
            session_commit();
            return true;
        }
        return false;
    }

    /*
     * Helper functions for unitest
     */

    /**
     * @throws Exception
     * @ignore
     */
    public static function clear_people()
    {
        assert(parent::$env == "dev", "Forbidden in production environment");
        $sql = "DELETE FROM People WHERE idPerson != -1";
        self::connect();
        self::executeStatement($sql);
        self::close();
        assert(parent::$error != '00000');
    }

    /**
     * @throws Exception
     * @ignore
     */
    public static function clear_inscription()
    {
        assert(parent::$env == "dev", "Forbidden in production environment");
        $sql = "DELETE FROM Inscription WHERE idInscription != -1";
        self::connect();
        self::executeStatement($sql);
        self::close();
        assert(parent::$error != '00000');
    }

    /**
     * @param $dumpfile
     * @throws Exception
     * @ignore
     */
    public static function restore_dump($dumpfile)
    {
        assert(parent::$env == "dev", "Forbidden in production environment");
        $fd = fopen($dumpfile, 'r');
        $sql = fread($fd, filesize($dumpfile));
        $transstmt = new StatementHelper($sql);
        self::connect();
        self::executeTransactionStatement($transstmt);
        self::close();
    }

}

