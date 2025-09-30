<?php

namespace Reddit\models;
use Reddit\models\Db;


class User extends Db
{
  public $username;
  public $email;
  public $password;

  public function existsUsername(string $username): bool
  {
    $stmt = $this->connection->prepare("SELECT * FROM user WHERE username = :username ");
    $stmt->bindParam(':username',$username);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }
}