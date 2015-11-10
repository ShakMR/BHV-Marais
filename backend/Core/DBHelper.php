<?php
/**
 * Created by PhpStorm.
 * User: Borja
 * Date: 09/11/15
 */

class DBHelper {
    private static $hostname;
    private static $username;
    private static $dbname;
    private static $password;
    protected static $db;
    private static $inifile;
    private static $env='dev';

    protected static $result;


    public static function connect () {
        self::$db = new PDO ("mysql:host=".self::$hostname.";dbname=".self::$dbname,self::$username,self::$password);
    }

    private static function read_config_file() {
        $config = parse_ini_file(self::$inifile, true)[self::$inifile];
        self::$hostname = $config['host'];
        self::$dbname = $config['database'];
        self::$username = $config['user'];
        self::$password = $config['password'];
    }

    public static function executeStatement () {
        $param = func_get_args();
        $n = count($param);
        if ($n == 0)
            throw new Exception('At least one parameter needed');
        $sql = $param[0];
        $stmt = self::$db->prepare($sql);
        $i = 1;
        while ($i < $n)
        {
            $stmt->bindParam(':_'.$i, $param[$i]);
            ++$i;
        }
        $stmt->execute();
        //print_r($stmt);

        self::$result = $stmt->fetchAll();
        //print_r(self::$result);
    }

    public static function executeTransaction ($statements)
        // expected array of arrays
    {
        assert(is_array($statements));
        assert(is_)
    }


    protected function today()
    {
        $date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $date)));
    }
};

class StatementHelper {
    private $sql;
    private $parameters;

    function __construct($sql, $parameters=null) {
        assert(is_array($parameters));
        $this->sql = $sql;
        $this->parameters = $parameters;
    }

    public function add_parameter($param)
    {
        array_push($this->parameters, $param);
    }

}


