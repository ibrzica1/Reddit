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

if(!$session->sessionExists("username"))
{
    header("Location: ../index.php");
}

$userId = $session->getFromSession('user_id');


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

<div class="body-container">

    <div class="message-container">
        <p class="message"><?=$session->displayMessage()?></p>
    </div>

    <h2>Tell us about your community</h2>
    <h4>A name and description help people understand what your 
        community is all about.</h4>
    <div class="form-container">
        <form action="../decisionMaker.php" method="post" enctype="multipart/form-data">
        <div class="input-container">
            <div class="name-container">
                <input type="text" name="name" id="nameInput" placeholder="Community name">
                <p class="name-letters">21</p>
            </div>
            <div class="description-container">
                <textarea name="description" id="descriptionInput" rows="10"
                placeholder="Description"></textarea>
                <p class="description-letters">0</p>
            </div>
            <div class="image-container">
                <input type="file" name="image" id="">
            </div>
        </div>
        <button type="submit">Create</button>
        </form>
        <div class="preview-container">
            <p class="prw-name">r/<span class="prw-name-span">
                communityname
            </span></p>
            <p class="prw-description">
                Your community description
            </p>
        </div>
    </div>
</div>

<script type="module">
    import {toggleNotification} from "../script/tools.js?v=<?php echo time(); ?>"; 
    import {generalSearch} from "../script/search.js?v=<?php echo time(); ?>";  
    import {checkDescriptionLength, checkNameLength} from "../script/textLength.js?v=<?php echo time(); ?>"; 
    
    const bellIcon = document.querySelector('.notifications-container');
    const notificationNum = document.querySelector('.notification-number');

    generalSearch(); // When you input something it will search through posts, community, users and display results
    checkDescriptionLength(); // Checks the length of community name, if its longer then 21 letters, stops the input also displays the preview
    checkNameLength(); // Checks the length of description text if its longer then 500 letters stops the input, also displays the preview

</script>
    
</body>

</html>