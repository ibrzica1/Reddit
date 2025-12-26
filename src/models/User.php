<?php

namespace Reddit\models;


class User
{
  public $id;
  public $username;
  public $email;
  public $password;
  public $bio;
  public $avatar;
  public $karma;
  public $time;

  public function __construct($array)
  {
    $this->id = $array['id'];
    $this->username = $array['username'];
    $this->email = $array['email'];
    $this->password = $array['password'];
    $this->bio = $array['bio'];
    $this->avatar = $array['avatar'];
    $this->karma = $array['karma'];
    $this->time = $array['time'];
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