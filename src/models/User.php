<?php

namespace Reddit\models;
use Reddit\models\Db;


class User extends Db
{
  public $username;
  public $email;
  public $password;
  public $bio;
  public $avatar;
  public $time;

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

  public function lengthBio(string $bio): bool
  {
    return strlen($bio) < 236;
  }

  public function existsAvatar(string $avatar): bool
  {
    $avatars = ["blue","green","greenBlue","lightBlue",
    "orange","pink","purple","yellow"];

    return in_array($avatar,$avatars)? true : false;
  }

  

  public function registerUser(string $username, string $email, string $password, 
  string $bio, string $avatar, string $time): void
  {
    $stmt = $this->connection->prepare("INSERT INTO user (username, email, password, bio, avatar, time)
    VALUES (:username, :email, :password, :bio, :avatar, :time)");
    $stmt->bindParam(':username',$username);
    $stmt->bindParam(':email',$email);
    $password = password_hash($password,PASSWORD_BCRYPT);
    $stmt->bindParam(':password',$password);
    $stmt->bindParam(':bio',$bio);
    $stmt->bindParam(':avatar',$avatar);
    $stmt->bindParam(':time',$time);

    $stmt->execute();
  }

  public function getUser(string $username): mixed
  {
    $stmt = $this->connection->prepare("SELECT * FROM user WHERE username = :username");
    $stmt->bindParam(':username',$username);
    $stmt->execute();

    return $stmt->fetch();
  }

  public function getUserAtribute(string $atribute, int $id): mixed
  {
    $stmt = $this->connection->prepare(
      "SELECT $atribute FROM user WHERE id = :id"
    );
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    return $stmt->fetch();
  }

  public function updateUser(string $atribute, string $value, int $id): void
  {
    $stmt = $this->connection->prepare("UPDATE user 
    SET $atribute = :atribute
    WHERE id = :id");
    $stmt->bindParam(':atribute',$value);
    $stmt->bindParam(':id',$id);
    $stmt->execute();
  }
}