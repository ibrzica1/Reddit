<?php

namespace Reddit\controllers;

use Reddit\repositories\UserRepository;
use Reddit\models\User;
use Reddit\services\SessionService;
use Reddit\services\MailService;
use Reddit\services\TimeService;

class UserController extends UserRepository
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

    $dbPassword = $user->getPassword();

    if(!password_verify($password,$dbPassword))
    {
      $message = "Password doesnt match";
      $session->setSession("message",$message);
      header("Location: view/login.php");
      exit();
    }

    $session->setSession("user_id",$user->getId());
    $session->setSession("username",$user->getUsername());
    $session->setSession("avatar",$user->getAvatar());

    header('Location: index.php');
  }

  public function signup(array $data): void
  {
    $session = new SessionService();
    $timeStamp = new TimeService();

    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    $confirmPassword = $data['password_confirm'];
    $bio = 'This is your bio';
    $avatar = 'blue';
    $time = $timeStamp->time;
    $karma = 0;

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

    if(!User::usernameLength($username))
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

    if(!User::checkPassword($password))
    {
      $message = "Password must contain special character, number, uppercase and lowercase letter";
      $session->setSession("message",$message);
      header("Location: view/signup.php");
      exit();
    }

    if(User::lengthPassword($password))
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

    $newUser = new User([
      'id' => NULL,
      'username' => $username,
      'email' => $email,
      'password' => password_hash($password, PASSWORD_BCRYPT),
      'bio' => $bio,
      'avatar' => $avatar,
      'karma' => $karma,
      'time' => $time
    ]);

    $this->registerUser($newUser);
    $user = $this->getUser($username);
    
    $session->setSession("user_id",$user->getId());
    $session->setSession("username",$username);
    $session->setSession("avatar",$avatar);

    $mailer = new MailService();
    $mailer->welcomeMail('test@inbox.mailtrap.io',$username);

    header('Location: index.php');
  }

  public function changeUsername(string $username): void
  {
    $session = new SessionService();
    $id = $session->getFromSession("user_id");
    $user = $this->getUserById($id);

    if(!isset($username) ||
      $username === "")
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

    if(!User::usernameLength($username))
    {
      $message = "Username length cant be smaller then 3 or longer than 15 characters";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    $oldUsername = $user->getUsername();
    $user->setUsername($username);

    $this->updateUser($user,$id);

    $newUser = $this->getUserById($id);
    $newUsername = $newUser->getUsername();
    
    if($oldUsername !== $newUsername)
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
    $user = $this->getUserById($id);

    if(!isset($email) ||
      $email === "")
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

    $oldEmail = $user->getEmail();
    $user->setEmail($email);

    $this->updateUser($user, $id);
    
    $newUser = $this->getUserById($id);
    $newEmail = $newUser->getEmail();

    if($oldEmail !== $newEmail)
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
    $user = $this->getUserById($id);

    if(!isset($oldPass) ||
      $oldPass === "")
    {
      $message = "You didnt send old password";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }
    if(!isset($newPass) ||
      $newPass === "")
    {
      $message = "You didnt send new password";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }
    if(!isset($confirmPass) ||
      $confirmPass === "")
    {
      $message = "You didnt send confirm password";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }
   

    $dbPassword = $user->getPassword();

    if(!password_verify($oldPass,$dbPassword))
    {
      $message = "Old password doesnt match";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    if(!User::checkPassword($newPass))
    {
      $message = "Password must contain special character, number, uppercase and lowercase letter";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    if(User::lengthPassword($newPass))
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

    $beforePass = $user->getPassword();
    $user->setPassword($newPass);
    
    $this->updateUser($user, $id);

    $newUser = $this->getUserById($id);
    $afterPass = $newUser->getPassword();

    if($beforePass !== $afterPass)
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

  public function changeBio($bio)
  {
    $session = new SessionService();
    $id = $session->getFromSession("user_id");
    $user = $this->getUserById($id);

    if(!isset($bio) ||
      $bio === "")
    {
      $message = "You didnt send new bio";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    if(!User::lengthBio($bio))
    {
      $message = "Bio cant be longer then 235 letters";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    $user->setBio($bio);
    $this->updateUser($user, $id);
    header("Location: view/settings.php");
    exit();
  }

  public function changeAvatar($avatar)
  {
    $session = new SessionService();
    $id = $session->getFromSession("user_id");
    $user = $this->getUserById($id);

    if(!isset($avatar) ||
      $avatar === "")
    {
      $message = "You didnt send avatar value";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    if(!User::existsAvatar($avatar))
    {
      $message = "Avatar doesnt exists";
      $session->setSession("message",$message);
      header("Location: view/settings.php");
      exit();
    }

    $user->setAvatar($avatar);
    $this->updateUser($user, $id);
    $session->setSession('avatar',$avatar);
    header("Location: view/profile.php");
    exit();
  }
}