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
    /** @var PDO self::$db*/
    protected static $db;
    protected static $inifile='mysql.ini';
    protected static $env='dev';

    /** @var  array $result */
    protected static $result;
    protected static $error;


    /**
     *
     */
    public static function connect () {
        self::read_config_file();
        self::$db = new PDO ("mysql:host=".self::$hostname.";dbname=".self::$dbname,self::$username,self::$password);
    }

    /**
     *
     */
    private static function read_config_file() {
        $config = parse_ini_file(self::$inifile, true)[self::$env];
        self::$hostname = $config['host'];
        self::$dbname = $config['database'];
        self::$username = $config['user'];
        self::$password = $config['password'];
    }

    /**
     * @throws Exception
     */
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

        if (strpos($sql, "SELECT") !== false) {
            self::$result = $stmt->fetchAll();
        }
        if ($stmt->errorCode() != '00000')
        {
            throw new Exception($stmt->errorInfo()[2]);
        }
        //print_r(self::$result);
    }

    /**
     * @param $statements
     * @throws Exception
     */
    public static function executeTransaction ($statements)
        // expected array of arrays
    {
        assert(is_array($statements));
        assert(get_class($statements[0]) == StatementHelper::TAG);

        self::$db->beginTransaction();
        try {
            foreach ($statements as $statement) {
                $stmt = self::$db->prepare($statement->get_SQL());
                $params = $statement->get_params();
                $stmt->execute($params);
               /* $n = count($params);
                $i = 1;
                while ($i < $n)
                {
                    $stmt->bindParam(':_'.$i, $params[$i]);
                    ++$i;
                }*/
            }
        }
        catch (Exception $exception) {
            self::$db->rollBack();
            self::$db->close();
            throw $exception;
        }
    }

    /**
     *
     */
    public static function close() {
        self::$db = null;
    }


    /**
     * @return bool|string
     */
    public function today()
    {
        return date("Y-m-d H:i:s");
    }
};

/**
 * Class StatementHelper
 */
class StatementHelper {
    private $sql;
    private $parameters;
    const TAG = "StatementHelp";

    function __construct($sql, $parameters=array()) {
        assert(is_array($parameters));
        $this->sql = $sql;
        $this->parameters = $parameters;
    }

    /**
     * @param $param
     */
    public function add_parameter($param)
    {
        array_push($this->parameters, $param);
    }

    public function get_SQL(){
        return $this->sql;
    }

    public function get_params()
    {
        return $this->parameters;
    }

}


