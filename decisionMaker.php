<?php

require_once "vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\controllers\UserController;

$session = new SessionService();

if(isset($_POST['login']))
{
  $userController = new UserController();
  $userController->login($_POST);
}

if(isset($_POST['signup']))
{
  $userController = new UserController();
  $userController->signup($_POST);
}

if(isset($_POST['username']))
{
  $username = $_POST['username'];

  $userController = new UserController();
  $userController->changeUsername($username);
}

if(isset($_POST['email']))
{
  $email = $_POST['email'];

  $userController = new UserController();
  $userController->changeEmail($email);
}

if(isset($_POST['old-password']) 
&& isset($_POST['new-password'])
&& isset($_POST['confirm-password']))
{
  $oldPass = $_POST['old-password'];
  $newPass = $_POST['new-password'];
  $confirmPass = $_POST['confirm-password'];

  $userController = new UserController();
  $userController->changePassword($oldPass, $newPass, $confirmPass);
}

if(isset($_POST['bio']))
{
  $bio = $_POST['bio'];

  $userController = new UserController();
  $userController->changeBio($bio);
}

if(isset($_POST['avatar']))
{
  $avatar = $_POST['avatar'];

  $userController = new UserController();
  $userController->changeAvatar($avatar);
}