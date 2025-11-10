<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
$session = new SessionService();

if(!$session->sessionExists("username"))
{
    header("Location: ../index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Community</title>
    <link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/createCommunity.css?v=<?php echo time(); ?>">
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
            <img class="user-avatar" src="../images/avatars/<?= $session->getFromSession('avatar')?>.webp">
        </div>
        <div class="menu-container" id="userMenu">
            <a class="profile-container" href="profile.php">
        <div class="avatar-container">
            <img class="user-avatar" src="../images/avatars/<?= $session->getFromSession('avatar')?>.webp">
        </div>
        <div class="info-container">
            <h3>View Profile</h3>
            <p>u/<?= $session->getFromSession("username") ?></p>
        </div>
        </a>
        <a class="edit-container" href="../view/editAvatar.php">
            <img src="../images/icons/shirt.png">
            <p>Edit Avatar</p>
        </a>
        <a class="logout-container" href="../src/controllers/Logout.php">
            <img src="../images/icons/house-door.png">
            <p>Log Out</p>
        </a>
    </div>
    </div>
</div>

<div class="body-container">
    <h2>Tell us about your community</h2>
    <h4>A name and description help people understand what your 
        community is all about.</h4>
    <div class="form-container">
        <form action="" method="post">
        <div class="input-container">
            <div class="name-container">
                <input type="text">
                <p class="name-letters">21</p>
            </div>
            <div class="description-container">
                <input type="text">
                <p class="description-letters">0</p>
            </div>
        </div>
        <button type="submit">Create</button>
        </form>
        <div class="preview-container">
            <p class="prw-name">r/<span class="prw-name-span">
                communityname
            </span></p>
            <p>1 weekly visitor - 1 weekly contributor</p>
            <p class="prw-description">
                Your community description
            </p>
        </div>
    </div>
</div>

<script type="module">
    import { toggleMenu} from "../script/tools.js?v=<?php echo time(); ?>";
    const  menu = document.getElementById("userInfo");
    menu.addEventListener('click',toggleMenu);
</script>
    
</body>

</html>