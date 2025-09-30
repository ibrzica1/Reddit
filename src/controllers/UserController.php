<?php

namespace Reddit\controllers;

use Reddit\models\User;
use Reddit\services\SessionService;

class UserController extends User
{
  public function signup(array $data)
  {
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
      die("Username already exists");
    }

  }
}