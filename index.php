<?php

require_once "vendor/autoload.php";

use Reddit\services\SessionService;

$session = new SessionService();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Index</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="style/header.css">
</head>

<body>
  
  <div class="header-container">
    <div class="logo-container">
        <img src="images/reddit.png" alt="Reddit Logo" class="reddit-logo">
    </div>
    
    <div class="search-container">
        <img src="images/icons/magnifying-glass.png" alt="Search Icon" class="search-icon">
        <input type="text" placeholder="Search Reddit">
    </div>
    
    <?php if($session->sessionExists("username")): ?>
      <div class="buttons-container">
            <a href="view/createPost.php" class="create-post-btn" title="Create Post">
                <img class='plus-icon' src="images/icons/plus.png">
                <p>Create</p>
            </a>
            <div class="user-dropdown">
                <div class="user-info">
                    <img class="user-avatar" src="images/avatars/avatarBlue.webp">
                </div>
            </div>
        </div>
    <?php else: ?>
    <div class="buttons-container">
        <div class="login-container">
            <a href="view/login.php">Log In</a>
        </div>
        <div class="signup-container">
            <a href="view/signup.php">Sign Up</a>
        </div>
    </div>
    <?php endif; ?>
  </div>
    
</body>

</html>