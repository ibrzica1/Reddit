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
      die("Niste prosledili username");
    }

    if(!isset($data['email']))
    {
      die("Niste prosledili email");
    }

    if(!isset($data['password']))
    {
      die("Niste prosledili sifru");
    }
    
    if(!isset($data['password_confirm']))
    {
      die("Niste prosledili ponovljenu sifru");
    }

    if($this->existsUsername($data['username']))
    {
      $message = "Username already exists";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

  }
}