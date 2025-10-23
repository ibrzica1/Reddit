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
  <link rel="stylesheet" href="style/header.css?v=<?php echo time(); ?>">
  
</head>

<body>
  
  <div class="header-container">
    <a class="logo-container" href="index.php">
        <img src="images/reddit.png" alt="Reddit Logo" class="reddit-logo">
    </a>
    
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
            <a class="notifications-container" href="">
                <img src="images/icons/bell.png">
            </a>
                <div class="user-info" id="userInfo">
                    <div class="green-dot"></div>
                    <img class="user-avatar" src="images/avatars/avatarBlue.webp">
                    
                </div>
                <div class="menu-container" id="userMenu">
                        <div class="profile-container">
                            <div class="avatar-container">
                                <img class="user-avatar" src="images/avatars/avatarBlue.webp">
                            </div>
                            <div class="info-container">
                                <h3>View Profile</h3>
                                <p>u/<?= $session->getFromSession("username") ?></p>
                            </div>
                        </div>
                        <div class="edit-container">
                            <img src="images/icons/shirt.png">
                            <p>Edit Avatar</p>
                        </div>
                        <div class="logout-container">
                            <img src="images/icons/house-door.png">
                            <p>Log Out</p>
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
    
  <script type="module">
    import { toggleMenu } from "./script/tools.js";
    const  menu = document.getElementById("userInfo");
    menu.addEventListener('click',toggleMenu);
  </script>
</body>

</html>