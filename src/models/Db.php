<?php

namespace Reddit\models;

class Db
{

  public $connection;

  public function __construct()
  {

      $this->connection = new \PDO("mysql:host=localhost;dbname=Reddit", "root", "");

  }
}