<?php

require_once "vendor/autoload.php";

use Reddit\controllers\CommunityController;
use Reddit\services\SessionService;
use Reddit\controllers\UserController;
use Reddit\controllers\PostController;

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

if(isset($_POST['username-update']))
{
  $username = $_POST['username-update'];

  $userController = new UserController();
  $userController->changeUsername($username);
}

if(isset($_POST['email-update']))
{
  $email = $_POST['email-update'];

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

if(isset($_POST['bio-update']))
{
  $bio = $_POST['bio-update'];

  $userController = new UserController();
  $userController->changeBio($bio);
}

if(isset($_POST['avatar-update']))
{
  $avatar = $_POST['avatar-update'];

  $userController = new UserController();
  $userController->changeAvatar($avatar);
}

if(isset($_POST['title']) && !empty(trim($_POST['text'])))
{
  $title = $_POST['title'];
  $text = $_POST['text'];

  $postController = new PostController();
  $postController->textPost($title, $text);
}

if(isset($_POST['title']) && !empty($_FILES['image']['name'][0]))
{
  $title = $_POST['title'];
  $images = $_FILES['image'];

  $postController = new PostController();
  $postController->imagePost($title, $images);
}

if(isset($_POST['name']) && isset($_POST['description']))
{
  $name = $_POST['name'];
  $description = $_POST['description'];

  $communityController = new CommunityController();
  $communityController->createCommunity($name,$description);
}

if(isset($_POST['community']))
{
  $community = $_POST['community'];

  $profileController = new ProfileController();
  $profileController->showProfile($community);
}