<?php

namespace Reddit\controllers;

use Reddit\models\User;
use Reddit\services\SessionService;

class UserController extends User
{
  public function signup(array $data)
  {
    $session = new SessionService();

    if(!isset($data['username']))
    {
      $message = "You didnt send confim username";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    if(!isset($data['email']))
    {
      $message = "You didnt send confim email";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    if(!isset($data['password']))
    {
      $message = "You didnt send password";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }
    
    if(!isset($data['password_confirm']))
    {
      $message = "You didnt send confim password";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    if($this->existsUsername($data['username']))
    {
      $message = "Username already exists";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    if(!$this->usernameLength($data['username']))
    {
      $message = "Username length cant be smaller then 3 or longer then 15 characters";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

  }
}