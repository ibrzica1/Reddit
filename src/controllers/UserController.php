<?php

namespace Reddit\controllers;

use Reddit\models\User;
use Reddit\services\SessionService;
use Reddit\services\MailService;

class UserController extends User
{

  public function login(array $data): void
  {
    $session = new SessionService();

    $username = $data['username'];
    $password = $data['password'];

    if(!isset($username))
    {
      $message = "You didnt send confim username";
      $session->setSession("message",$message);
      header("Location: view/login.php");
      exit();
    }

    if(!isset($password))
    {
      $message = "You didnt send password";
      $session->setSession("message",$message);
      header("Location: view/login.php");
      exit();
    }

    $user = $this->getUser($username);

    if(empty($user))
    {
      $message = "Username doesnt exists";
      $session->setSession("message",$message);
      header("Location: view/login.php");
      exit();
    }

    $dbPassword = $user['password'];

    if(!password_verify($password,$dbPassword))
    {
      $message = "Password doesnt match";
      $session->setSession("message",$message);
      header("Location: view/login.php");
      exit();
    }

    $session->setSession("user_id",$user['id']);
    $session->setSession("username",$user['username']);

    header('Location: index.php');
  }

  public function signup(array $data): void
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
    $user = $this->getUser($username);
    
    $session->setSession("user_id",$user['id']);
    $session->setSession("username",$user['username']);
    $session->setSession("logged",true);

    $mailer = new MailService();
    $mailer->welcomeMail('test@inbox.mailtrap.io',$username);

    header('Location: index.php');
  }

  public function changeUsername(string $username): void
  {
    $session = new SessionService();
    $id = $session->getFromSession("user_id");

    if(!isset($username))
    {
      $message = "You didnt send username";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    if($this->existsUsername($username))
    {
      $message = "Username already exists";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    if(!$this->usernameLength($username))
    {
      $message = "Username length cant be smaller then 3 or longer than 15 characters";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    $oldUsername = $this->getUserAtribute('username',$id);

    $this->updateUser('username', $username, $id);

    $newUsername = $this->getUserAtribute('username',$id);
    
    if($oldUsername[0] !== $newUsername[0])
    {
      $message = "Username was succesfully changed";
      $session->setSession("message",$message);
      $session->setSession("username",$username);
      header("Location: view/settings.php");
      exit();
    }
    else
    {
      $message = "Username was not succesfully changed";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }
  }

  public function changeEmail(string $email): void
  {
    $session = new SessionService();
    $id = $session->getFromSession("user_id");

    if(!isset($email))
    {
      $message = "You didnt send email";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    if($this->existsEmail($email))
    {
      $message = "Email already exists";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    $oldEmail = $this->getUserAtribute('email',$id);

    $this->updateUser('email', $email, $id);
    
    $newEmail = $this->getUserAtribute('email',$id);

    if($oldEmail[0] !== $newEmail[0])
    {
      $message = "Email was succesfully changed";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }
    else
    {
      $message = "Email was not succesfully changed";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }
  }
  
  public function changePassword($oldPass, $newPass, $confirmPass)
  {
    $session = new SessionService();
    $id = $session->getFromSession("user_id");
    $username = $session->getFromSession("username");

    if(!isset($oldPass))
    {
      $message = "You didnt send old password";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }
    if(!isset($newPass))
    {
      $message = "You didnt send new password";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }
    if(!isset($confirmPass))
    {
      $message = "You didnt send confirm password";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }
   
    $user = $this->getUser($username);

    $dbPassword = $user['password'];

    if(!password_verify($oldPass,$dbPassword))
    {
      $message = "Old password doesnt match";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    if(!$this->checkPassword($newPass))
    {
      $message = "Password must contain special character, number, uppercase and lowercase letter";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    if($this->lengthPassword($newPass))
    {
      $message = "Password length cant be smaller than 6 characters";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    if($newPass !== $confirmPass)
    {
      $message = "New password and confirm password doesnt match";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    $beforePass = $this->getUserAtribute('password', $id);
    $password = password_hash($newPass,PASSWORD_BCRYPT);
    
    $this->updateUser('password', $password, $id);

    $afterPass = $this->getUserAtribute('password', $id);

    if($beforePass[0] !== $afterPass[0])
    {
      $message = "Password was succesfully changed";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }
    else
    {
      $message = "Password was not succesfully changed";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }
  }
}