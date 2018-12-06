<?php

class DB {
    protected static $instance;
    protected function __construct() {}
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $db_info = [
                "db_host" => "127.0.0.1",
                "db_port" => "33060",
                "db_user" => "homestead",
                "db_pass" => "secret",
                "db_name" => "apiex"
            ];
            try {
                self::$instance = new PDO(
                    "mysql:host=" . $db_info['db_host'] .
                    ';port=' . $db_info['db_port'] .
                    ';dbname=' . $db_info['db_name'],
                    $db_info['db_user'],
                    $db_info['db_pass']);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                self::$instance->query('SET NAMES utf8mb4');
            } catch(PDOException $error) {
                echo $error->getMessage();
            }
        }
        return self::$instance;
    }
}