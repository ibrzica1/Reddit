<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\models\User;

$session = new SessionService();

if(!$session->sessionExists("username")) {
    header("Location: ../index.php");
}

$user = new User();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/settings.css?v=<?php echo time(); ?>">
</head>

<body>

<div class="header-container">
    <a class="logo-container" href="../index.php">
        <img src="../images/reddit.png" alt="Reddit Logo" class="reddit-logo">
    </a>

    <div class="search-container">
        <img src="../images/icons/magnifying-glass.png" alt="Search Icon" class="search-icon">
        <input type="text" placeholder="Search Reddit">
    </div>

    <div class="buttons-container">
        <a href="../view/createPost.php" class="create-post-btn" title="Create Post">
            <img class='plus-icon' src="../images/icons/plus.png">
            <p>Create</p>
        </a>
        <a class="notifications-container" href="">
            <img src="../images/icons/bell.png">
        </a>
        <div class="user-info" id="userInfo">
            <div class="green-dot"></div>
            <img class="user-avatar" src="../images/avatars/avatarBlue.webp">
        </div>
        <div class="menu-container" id="userMenu">
            <a class="profile-container" href="profile.php">
                <div class="avatar-container">
                    <img class="user-avatar" src="../images/avatars/avatarBlue.webp">
                </div>
                <div class="info-container">
                    <h3>View Profile</h3>
                    <p>u/<?= $session->getFromSession("username") ?></p>
                </div>
            </a>
            <div class="edit-container">
                <img src="../images/icons/shirt.png">
                <p>Edit Avatar</p>
            </div>
            <a class="logout-container" href="../src/controllers/Logout.php">
                <img src="../images/icons/house-door.png">
                <p>Log Out</p>
            </a>
        </div>
    </div>
</div>

<div class="main-container">
    <h1>Settings</h1>
    <div class="fields-container">
        <div class="form">
                <p>Username</p>
                <button class="edit-btn" data-target="username-form">Edit</button>
        </div>
        <form action="" method="post" id="username-form">
            <p>New Username</p>
            <input type="text">
            <button>Submit</button>
        </form>
        <div class="form">
                <p>Email</p>
                <button class="edit-btn"  data-target="email-form">Edit</button>
        </div>
        <form action="" method="post" id="email-form">
            <p>New Email</p>
            <input type="text">
            <button>Submit</button>
        </form>
        <div class="form">
                <p>Password</p>
                <button class="edit-btn"  data-target="password-form">Edit</button>
        </div>
        <form action="" method="post" id="password-form">
            <p>OldPassword</p>
            <input type="text">
            <p>New Password</p>
            <input type="text">
            <p>Repeat Password</p>
            <input type="text">
            <button>Submit</button>
        </form>
        <div class="form">
                <p>Bio</p>
                <button class="edit-btn"  data-target="bio-form">Edit</button>
        </div>
        <form action="" method="post" id="bio-form">
            <p>New Bio</p>
            <input type="text">
            <button>Submit</button>
        </form>
        <div class="form">
                <p>Avatar</p>
                <button id="edit-btn" value="avatar">Edit</button>
        </div>
        
    </div>
</div>

<script type="module">
    import { toggleMenu } from "../script/tools.js";
    const menu = document.getElementById("userInfo");
    menu.addEventListener('click', toggleMenu);

    const editBtn = document.querySelectorAll(".edit-btn");

    editBtn.forEach(button => {
      button.addEventListener('click', ()=>{
        const targetFormId = button.getAttribute('data-target');
        const targetForm = document.getElementById(targetFormId);

        if (targetForm) {
                if (targetForm.style.display === "none" || targetForm.style.display === "") {
                    targetForm.style.display = "flex"; 
                    button.textContent = "Cancel"; 
                } else {
                    targetForm.style.display = "none"; 
                    button.textContent = "Edit";
                }
            }
    });

});
    

</script>
</body>
</html>
