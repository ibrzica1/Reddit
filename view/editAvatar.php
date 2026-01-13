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
    <title>Edit Avatar</title>
    <link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/editAvatar.css?v=<?php echo time(); ?>">
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
<div class="banner"></div>
<div class="body-container">
    <div class="form-container">
        <form action="../decisionMaker.php" method="post">
            <img src="../images/avatars/<?= $session->getFromSession('avatar')?>.webp" class="selected-avatar">
            <input type="hidden" value="" class="form-input" name="avatar-update">
            <button type="submit">Change</button>
        </form>
    </div>

    <div class="avatar-grid">
        <div class="image-wrapper" data-target="blue">
            <img src="../images/avatars/blue.webp">
        </div>
        <div class="image-wrapper" data-target="green">
            <img src="../images\avatars\green.webp">
        </div>
        <div class="image-wrapper" data-target="greenBlue">
            <img src="../images\avatars\greenBlue.webp">
        </div>
        <div class="image-wrapper" data-target="lightBlue">
            <img src="../images\avatars\lightBlue.webp">
        </div>
        <div class="image-wrapper" data-target="orange">
            <img src="../images\avatars\orange.webp">
        </div>
        <div class="image-wrapper" data-target="pink">
            <img src="../images\avatars\pink.webp">
        </div>
        <div class="image-wrapper" data-target="purple">
            <img src="../images\avatars\purple.webp">
        </div>
        <div class="image-wrapper" data-target="yellow">
            <img src="../images\avatars\yellow.webp">
        </div>
    </div>
</div>

<script type="module">
    import {toggleNotification} from "../script/tools.js?v=<?php echo time(); ?>";
    import {generalSearch} from "../script/search.js?v=<?php echo time(); ?>"; 
    import {changeAvatar, changeBanner} from "../script/avatar.js?v=<?php echo time(); ?>"; 

    generalSearch(); // General Search is searching everything community, posts, users and displays results
    changeAvatar(); // changeAvatar takes value of selected avatar and immidiatly changes avatat
    changeBanner('<?=$session->getFromSession('avatar')?>'); // changes banner color with the color of selected avatar

</script>
</body>

</html>