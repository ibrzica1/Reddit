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

  public function checkPassword(string $password): bool
  {
    return preg_match('/[^A-Za-z0-9]/', $password) && preg_match('/[0-9]/', $password) && 
    preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password);
  }

  public function lengthPassword(string $password): bool
  {
    return strlen($password) < 6;
  }

  public function registerUser(string $username, string $email, string $password): void
  {
    $stmt = $this->connection->prepare("INSERT INTO user (username, email, password)
    VALUES (:username, :email, :password)");
    $stmt->bindParam(':username',$username);
    $stmt->bindParam(':email',$email);
    $password = password_hash($password,PASSWORD_BCRYPT);
    $stmt->bindParam(':password',$password);

    $stmt->execute();
  }

  public function getUser(string $username): mixed
  {
    $stmt = $this->connection->prepare("SELECT * FROM user WHERE username = :username");
    $stmt->bindParam(':username',$username);
    $stmt->execute();

    return $stmt->fetch();
  }
}