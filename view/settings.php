<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\repositories\UserRepository;
use Reddit\repositories\CommunityRepository;
use Reddit\repositories\CommentRepository;
use Reddit\repositories\PostRepository;

$user = new UserRepository();
$community = new CommunityRepository();
$post = new PostRepository();
$comment = new CommentRepository();
$session = new SessionService();

if(!$session->sessionExists("username")) {
    header("Location: ../index.php");
}

$userId = $session->getFromSession('user_id');

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
        <img src="../images/logo.png" alt="Reddit Logo" class="reddit-logo">
    </a>

    <div class="search-container">
        <img src="../images/icons/magnifying-glass.png" alt="Search Icon" class="search-icon">
        <input type="text" placeholder="Search Reddit" id="searchInput">
        <div class="search-results" id="searchResults"></div>
    </div>

    <div class="buttons-container">
        <a href="../view/createPost.php" class="create-post-btn" title="Create Post">
            <img class='plus-icon' src="../images/icons/plus.png">
            <p>Create</p>
        </a>
    
    <?php include __DIR__ . "/partials/notificationHtml.php" ?>
    <?php include __DIR__ . "/partials/menuHtml.php" ?>
        
    </div>
</div>

<div class="main-container">
    <div class="message-container">
      <p class="message"><?=$session->displayMessage()?></p>
    </div>
    <h1>Settings</h1>
    <div class="fields-container">
        <div class="form">
                <p>Username</p>
                <button class="edit-btn" data-target="username-form">Edit</button>
        </div>
        <form action="../decisionMaker.php" method="post" id="username-form">
            <p>New Username</p>
            <input type="text" name="username-update">
            <button>Submit</button>
        </form>
        <div class="form">
                <p>Email</p>
                <button class="edit-btn"  data-target="email-form">Edit</button>
        </div>
        <form action="../decisionMaker.php" method="post" id="email-form">
            <p>New Email</p>
            <input type="text" name="email-update">
            <button>Submit</button>
        </form>
        <div class="form">
                <p>Password</p>
                <button class="edit-btn"  data-target="password-form">Edit</button>
        </div>
        <form action="../decisionMaker.php" method="post" id="password-form">
            <p>OldPassword</p>
            <input type="text" name="old-password">
            <p>New Password</p>
            <input type="text" name="new-password">
            <p>Repeat Password</p>
            <input type="text" name="confirm-password">
            <button>Submit</button>
        </form>
        <div class="form">
            <p>Bio</p>
            <button class="edit-btn"  data-target="bio-form">Edit</button>
        </div>
        <form action="../decisionMaker.php" method="post" id="bio-form">
            <p>New Bio (<span class="letters">235</span> Letters)</p>
            <textarea name="bio-update" id="bioId" rows="4"
            placeholder="Enter your Bio"></textarea>
            <button>Submit</button>
        </form>
        <div class="form">
            <p>Avatar</p>
            <button class="edit-btn"  data-target="avatar-form">Edit</button>
        </div>
        <form action="../decisionMaker.php" method="post" id="avatar-form">
            <div class="avatar-grid" id="avatarGrid">
                <div class="image-wrapper" data-target="blue">
                <img src="../images/avatars/blue.webp">
                <input type="checkbox" name="avatar-update" id="blue" value="blue">
                </div>
                <div class="image-wrapper" data-target="green">
                    <img src="../images\avatars\green.webp">
                    <input type="checkbox" name="avatar-update" id="green" value="green">
                </div>
                <div class="image-wrapper" data-target="greenBlue">
                    <img src="../images\avatars\greenBlue.webp">
                    <input type="checkbox" name="avatar-update" id="greenBlue" value="greenBLue">
                </div>
                <div class="image-wrapper" data-target="lightBlue">
                    <img src="../images\avatars\lightBlue.webp">
                    <input type="checkbox" name="avatar-update" id="lightBlue" value="lightBlue">
                </div>
                <div class="image-wrapper" data-target="orange">
                    <img src="../images\avatars\orange.webp">
                    <input type="checkbox" name="avatar-update" id="orange" value="orange">
                </div>
                <div class="image-wrapper" data-target="pink">
                    <img src="../images\avatars\pink.webp">
                    <input type="checkbox" name="avatar-update" id="pink" value="pink">
                </div>
                <div class="image-wrapper" data-target="purple">
                    <img src="../images\avatars\purple.webp">
                    <input type="checkbox" name="avatar-update" id="purple" value="purple">
                </div>
                <div class="image-wrapper" data-target="yellow">
                    <img src="../images\avatars\yellow.webp">
                    <input type="checkbox" name="avatar-update" id="yellow" value="yellow">
                </div>
            </div>
            <button>Submit</button>
        </form>
    </div>
</div>

<script type="module">
    import {toggleEditForms} from "../script/tools.js?v=<?php echo time(); ?>";
    import {generalSearch} from "../script/search.js?v=<?php echo time(); ?>";  
    import {checkboxesAvatar} from "../script/avatar.js?v=<?php echo time(); ?>"; 
    import {checkBioLength} from "../script/textLength.js?v=<?php echo time(); ?>";
    
    const bellIcon = document.querySelector('.notifications-container');
    const notificationNum = document.querySelector('.notification-number');

    generalSearch();
    checkboxesAvatar();
    toggleEditForms();
    checkBioLength();

</script>
</body>
</html>
