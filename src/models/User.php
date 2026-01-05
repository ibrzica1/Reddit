<?php

namespace Reddit\models;


class User
{
  private ?int $id;
  private string $username;
  private string$email;
  private string $password;
  private string $bio;
  private string $avatar;
  private int $karma;
  private string $time;

  public function __construct($array)
  {
    $this->id = $array['id'] ?? NULL;
    $this->username = $array['username'];
    $this->email = $array['email'];
    $this->password = $array['password'];
    $this->bio = $array['bio'];
    $this->avatar = $array['avatar'];
    $this->karma = $array['karma'];
    $this->time = $array['time'];
  }

  public function setUsername(string $username): void
  {
      if (!self::usernameLength($username)) {
          throw new \InvalidArgumentException("Username length invalid");
      }
      $this->username = $username;
  }

  public function setEmail(string $email): void
  {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          throw new \InvalidArgumentException("Invalid email");
      }
      $this->email = $email;
  }

  public function setPassword(string $password): void
  {
      if (!self::checkPassword($password)) {
          throw new \InvalidArgumentException("Weak password");
      }
      $this->password = password_hash($password, PASSWORD_BCRYPT);
  }

  public function setBio(string $bio): void
  {
     if (!self::lengthBio($bio)) {
       throw new \InvalidArgumentException("Bio length is too long");
     }
     $this->bio = $bio;
  }

  public function setAvatar(string $avatar): void
  {
    if(!self::existsAvatar($avatar)){
      throw new \InvalidArgumentException("Avatar doesnt exist");
    }
    $this->avatar = $avatar;
  }

  public function setKarma(string $karma): void
  {
    $this->karma = $karma;
  }

  public function setTime(string $time): void
  {
    $this->time = $time;
  }

  public function getId(): ?int
  {
      return $this->id;
  }

  public function getUsername(): string
  {
      return $this->username;
  }

  public function getEmail(): string
  {
      return $this->email;
  }

  public function getPassword(): string
  {
      return $this->password;
  }

  public function getBio(): string
  {
      return $this->bio;
  }

  public function getAvatar(): string
  {
      return $this->avatar;
  }

  public function getKarma(): string
  {
      return $this->karma;
  }

  public function getTime(): string
  {
      return $this->time;
  }

  public static function usernameLength(string $username): bool
  {
    return strlen($username) > 2 && strlen($username) < 16;
  }

  public static function checkPassword(string $password): bool
  {
    return preg_match('/[^A-Za-z0-9]/', $password) && preg_match('/[0-9]/', $password) && 
    preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password);
  }

  public static function lengthPassword(string $password): bool
  {
    return strlen($password) < 6;
  }

  public static function lengthBio(string $bio): bool
  {
    return strlen($bio) < 236;
  }

  public static function existsAvatar(string $avatar): bool
  {
    $avatars = ["blue","green","greenBlue","lightBlue",
    "orange","pink","purple","yellow"];

    return in_array($avatar,$avatars)? true : false;
  }

}