<?php

require_once 'config.php';

class Database
{
  private $connection;

  public function __construct()
  {
    $this->connect();
  }

  private function connect()
  {
    try {
      $this->connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("Connection failed: " . $e->getMessage());
    }
  }

  public function getConnection()
  {
    return $this->connection;
  }
}
