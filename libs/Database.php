<?php

/**
 * Created by PhpStorm.
 * User: josea
 * Date: 16/04/2022
 * Time: 1:09
 */
class Database
{
    public $pdo;
    private $host = 'localhost';
    private $port = '3306';
    private $user = 'root';
    private $password = '';
    private $tablename = 'csv';

    function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};port:{$this->port};charset=utf8mb4", $this->user, $this->password);
            $this->pdo->exec('USE ' . $this->tablename);
        } catch (Exception $e) {
            exit("Could not connect to Database. " . $e->getMessage());
        }
    }
}