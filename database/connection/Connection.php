<?php


namespace Database;

require __DIR__ . "/../../vendor/autoload.php";

class Connection
{
    private $connect;
    public function migrate()
    {
        require __DIR__ . "/../migrations/create_table_users.php";
    }

    public function __construct($location = 'sqlite:database/database.sqlite')
    {
        try {
            $this->connect = new \PDO($location);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function insert($params)
    {
        $name = $params['name'];
        $email = $params['email'];
        $password = $params['password'];
        try {
            $this->connect->exec("INSERT INTO users (name, email, password) VALUES ('{$name}', '{$email}', '{$password}')");
        } catch (\PDOException $e) {
            return $e;
        }
    }

    public function find($value)
    {
        try {
            return $this->connect->query("SELECT * FROM users WHERE email = '{$value}';")->fetchAll();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function index()
    {
        return $this->connect->query("SELECT * FROM users;")->fetchAll();
    }

}

