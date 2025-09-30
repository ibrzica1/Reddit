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

  public function usernameLength(string $username): bool
  {
    return strlen($username) > 2 && strlen($username) < 16;
  }

  public function existsEmail(string $email): bool
  {
    $stmt = $this->connection->prepare("SELECT * FROM user WHERE email = :email ");
    $stmt->bindParam(':email',$email);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }
}