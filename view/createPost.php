<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\repositories\UserRepository;
use Reddit\repositories\CommunityRepository;
use Reddit\repositories\CommentRepository;
use Reddit\repositories\PostRepository;
use Reddit\repositories\ImageRepository;

$session = new SessionService();
$user = new UserRepository();
$community = new CommunityRepository();
$post = new PostRepository();
$comment = new CommentRepository();
$image = new ImageRepository();
$communityId = "";

if(!empty($_GET["comm_id"]))
{
   $communityId = $_GET["comm_id"]; 
   $selectedCommunity = $community->getCommunity("id",$communityId);
   $commImage = $image->getCommunityImage($communityId);
}


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
    <title>Create Post</title>
    <link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/createPost.css?v=<?php echo time(); ?>">
</head>

<body>

<div class="header-container">
    <a class="logo-container" href="../index.php">
        <img src="../images/logo.png" alt="Reddit Logo" class="reddit-logo">
    </a>
<?php if(!empty($selectedCommunity)): ?>
    <div class="search-container">
        <img src="../images/icons/magnifying-glass.png" alt="Search Icon" class="search-icon">
        <div class="user-search-container">
            <img src="../images/community/<?=$commImage->name?>">
            <p>r/<?= $selectedCommunity->getName()?></p>
        </div>
        <input type="text" placeholder="Search in r/<?= $selectedCommunity->getName() ?>" id="searchInput" data-comm_id="<?= $communityId ?>">
        <div class="search-results" id="searchResults"></div>
    </div>
    <?php else: ?>
    <div class="search-container">
        <img src="../images/icons/magnifying-glass.png" alt="Search Icon" class="search-icon">
        <input type="text" placeholder="Search Reddit" id="searchInput">
        <div class="search-results" id="searchResults"></div>
    </div>   
    <?php endif; ?>
      <div class="buttons-container">
        <a href="../view/createPost.php" class="create-post-btn" title="Create Post">
            <img class='plus-icon' src="../images/icons/plus.png">
            <p>Create</p>
        </a>

    <?php include __DIR__ . "/partials/notificationHtml.php" ?>
    <?php include __DIR__ . "/partials/menuHtml.php" ?>
        
    </div>
  </div>

<div class="message-container">
    <p class="message"><?=$session->displayMessage()?></p>
</div>

<div>
   <h2>Create Post</h2>
</div>

<?php if(!empty($selectedCommunity)): ?>
<div class="community-container">
    <img src="../images/community/<?=$commImage->name?>">
    <p><span>r/</span><?= $selectedCommunity->getName() ?></p>
</div>
<?php endif; ?>
    
<div class="seek-container" data-selected="<?= !empty($selectedCommunity) ?>">
    <input type="text" name="community-search" id="search-input" placeholder="Search for community">
    <p id="search-display"></p>
</div>
    

<div class="options-container">
    <div class="text-option">Text</div>
    <div class="image-option">Image</div>
</div>

<div class="form-container">
    <form action="../decisionMaker.php" method="post" enctype="multipart/form-data">
        <input type="hidden" id="selectedCommunity" name="community" value="<?=$communityId?>">
        <div class="title-container">
            <input type="text" placeholder="Title" id="titleId" name="title">
            <p><span class="letters">300</span> Letters</p>
        </div>

        <div class="text-container">
            <textarea name="text" id="" placeholder="Body text (optional)"></textarea>
        </div>

        <div class="image-container">
            <input type="file" id="file-input" name="image[]" multiple>
        </div>
    <button type="submit">Post</button>
</form>
</div>

<script type="module">

import {togglePostOptions} from "../script/tools.js?v=<?php echo time(); ?>";
import {postSearch, toggleCommunitySearch, communitySearch} from "../script/search.js?v=<?php echo time(); ?>";
import {checkTitleLength} from "../script/textLength.js?v=<?php echo time(); ?>";

postSearch();
toggleCommunitySearch();
communitySearch();
checkTitleLength();
togglePostOptions();

</script>
    
</body>

</html>