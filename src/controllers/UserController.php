<?php

namespace Reddit\controllers;

use Reddit\models\User;
use Reddit\services\SessionService;

class UserController extends User
{
  public function signup(array $data)
  {
    $session = new SessionService();

    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    $confirmPassword = $data['password_confirm'];

    if(!isset($username))
    {
      $message = "You didnt send confim username";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    if(!isset($email))
    {
      $message = "You didnt send confim email";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    if(!isset($password))
    {
      $message = "You didnt send password";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }
    
    if(!isset($confirmPassword))
    {
      $message = "You didnt send confim password";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    if($this->existsUsername($username))
    {
      $message = "Username already exists";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    if(!$this->usernameLength($username))
    {
      $message = "Username length cant be smaller then 3 or longer then 15 characters";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    if($this->existsEmail($email))
    {
      $message = "Email already exists";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    if(!$this->checkPassword($password))
    {
      $message = "Password must contain special character, number, uppercase and lowercase letter";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    if($this->lengthPassword($password))
    {
      $message = "Password length cant be smaller than 6 characters";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    if($password !== $confirmPassword)
    {
      $message = "Confirm Password doesnt match";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    $this->registerUser($username,$email,$password);

  }
}