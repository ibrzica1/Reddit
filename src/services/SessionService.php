<?php

namespace Reddit\services;

class SessionService
{
  public function __construct()
  {
    if(session_status() == PHP_SESSION_NONE)
    {
      session_start();
    }
  }

  public function getFromSession(string $key): mixed
  {
    return $_SESSION[$key];
  }

  public function setSession(string $key, mixed $value): self
  {
    $_SESSION[$key] = $value;
    return $this;
  }

  public function displayMessage(): string
  {
    if(isset($_SESSION["message"]))
    {
      $message = htmlspecialchars($_SESSION["message"]);
      unset($_SESSION["message"]); 
      return $message;
    }
    return "";
  }
}