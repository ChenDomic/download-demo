<?php

/**
 * 数据库连接类
 */
class Db
{
    /**
     * $instance 保存Db实例
     *
     * @var null
     */
    private static $instance = null;
    /**
     * $config 数据库连接配置
     *
     * @var undefined
     */
    private $config = array();
    /**
     * $db pdo实例
     *
     * @var null
     */
    private $db = null;

    private function __construct()
    {
        $this->config = include 'd:/web/phplearn/download/config/database.php';
        $this->createDb();
    }

    /**
     * getInstance
     *
     * @return Db对象
     */
    public static function getInstance()
    {
        static::$instance instanceof self or static::$instance = new Db();
        return static::$instance;
    }

    /**
     * createDb
     *
     * @return void
     */
    private function createDb()
    {
        // 获取数据库配置项
        $host = $this->config['host'] . ':' . $this->config['port'];
        $port = $this->config['port'];
        $database = $this->config['database'];
        $username = $this->config['username'];
        $password = $this->config['password'];
        $charset = $this->config['charset'];
        $prefix = $this->config['prefix'];

        try {
            $instance = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
            $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $instance->exec("SET NAMES $charset");
        } catch (PDOException $e) {
            // TODO
            print($e->getMessage());
        }
        $this->db = $instance;
    }

    /**
     * query
     *
     * @param  mixed $sql
     * @param  mixed $mode
     *
     * @return bool|array
     */
    public function query($sql, $mode = PDO::FETCH_ASSOC)
    {

        $stmt = $this->db->query($sql);
        $stmt->setFetchMode($mode);
        $rows = $stmt->fetchAll();
        return $rows;
    }

    /**
     * execute
     *
     * @param mixed $sql
     * @return int|bool
     */
    public function execute($sql)
    {
        // 查询
        return $this->db->exec($sql);
    }
}
