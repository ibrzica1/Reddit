<?php

require_once "vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\controllers\UserController;

$session = new SessionService();

if(isset($_POST['signup']))
{
  $userController = new UserController();
  $userController->signup($_POST);
}