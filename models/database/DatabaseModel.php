<?php
namespace models\database;
class DatabaseModel{
    private $conn;
    private static $instance = null;

    private function __construct()
    {
        try{
            $host = DB_HOST;
            $db_name = DB_NAME;
            $charset = DB_CHARSET;
            $user = DB_USER;
            $pass = DB_PASS;
            $dsn = "mysql:host={$host};dbname={$db_name};charset={$charset}";
            $this->conn = new \PDO($dsn, $user, $pass, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, // Включаем исключения при ошибках
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, // Ассоциативный массив по умолчанию
                \PDO::ATTR_EMULATE_PREPARES => false, // Отключаем эмуляцию подготовленных запросов
            ]); // Отключаем эмуляцию подготовленных запросов
        }catch(\PDOException $e){
            die('Ошибка подключения к базе данных: ' . $e->getMessage());
        }
        
    }

    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance =  new self();
        }
        return self::$instance;
    }

    public function getConnection(){
        return $this->conn;
    }
}