<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\services\TimeService;
use Reddit\repositories\UserRepository;
use Reddit\repositories\CommunityRepository;
use Reddit\repositories\CommentRepository;
use Reddit\repositories\PostRepository;
use Reddit\repositories\ImageRepository;
use Reddit\repositories\LikeRepository;

$session = new SessionService();
$time = new TimeService();
$community = new CommunityRepository();
$image = new ImageRepository();
$post = new PostRepository();
$comment = new CommentRepository();
$user = new UserRepository();
$like = new LikeRepository();

if(!$session->sessionExists("username"))
{
    header("Location: ../index.php");
}

$get = $_GET['comm_id'];
if(!empty($_GET['nott_id']))
{
    $notificationId = $_GET['nott_id'];
    if(!empty($notificationId)) $notification->changeSeenStatus($notificationId,"true");
}
$communityId = intval($get);
$selectedCommunity = $community->getCommunity("id",$communityId);
$communityImage = $image->getCommunityImage($communityId);
$userId = $session->getFromSession("user_id");
$communityUserId = $selectedCommunity->getUser_id();
$communityPosts = $post->getPost("community_id",$communityId);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community</title>
    <link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/community.css?v=<?php echo time(); ?>">
</head>

<body>
    
<div class="header-container">
    <a class="logo-container" href="../index.php">
        <img src="../images/logo.png" alt="Reddit Logo" class="reddit-logo">
    </a>
    
    <div class="search-container">
        <img src="../images/icons/magnifying-glass.png" alt="Search Icon" class="search-icon">
        <div class="user-search-container">
            <img src="../images/community/<?=$communityImage->getName()?>">
            <p>r/<?= $selectedCommunity->getName() ?></p>
        </div>
        <input type="text" placeholder="Search in r/<?= $selectedCommunity->getName() ?>" id="searchInput" id="searchInput" data-comm_id="<?= $communityId ?>">
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
    <div class="banner" style="background-image: url('../images/community/banner.jpg');"></div>
    <div class="title-container">
        <div class="image-container">
            <img src="../images/community/<?=$communityImage->getName()?>">
        </div>
        <div class="name-container">
            <p><span>r/</span><?=$selectedCommunity->getName()?></p>
        </div>
        
        <a href="createPost.php?comm_id=<?=$selectedCommunity->getId()?>" class="create-post-container">
            <img src="../images/icons/add.png">
            <p>Create Post</p>
        </a>

        <?php if($communityUserId == $userId): ?>
            <form action="../decisionMaker.php" method="post">
                <input type="hidden" name="delete-community" value="<?=$selectedCommunity->getId()?>">
                <button class="delete-container">
                     <img src="../images/icons/set.png">
                </button>
            </form>
        <?php endif; ?>
    </div>

<div class="content-container">
<main class="posts-grid">
    <?php if(!empty($communityPosts)): ?>
    <?php foreach($communityPosts as $postItem): ?>
    <?php $postUser = $user->getUserByAttribute("id",$postItem->getUser_id()); ?>
    <?php $postId = $postItem->getId(); ?>
    <?php $postLikes = $like->getLike("post_id",$postId,$userId); ?>
    <?php if($postLikes === NULL): ?>
    <?php $postLikeStatus = "neutral"; ?>
    <?php else: ?>
    <?php $postLikeStatus = $postLikes->getStatus(); ?>
    <?php  endif;  ?>
    <?php $postImages = []; ?>
    <div class="post-container">
    <div class="post-user-container">
        <img src="../images/avatars/<?=$postUser->getAvatar()?>.webp">
        <p><span>u/</span><?= $postUser->getUsername() ?></p>
        <h3><?= $time->calculateTime($postItem->getTime()); ?></h3>
    </div>
    <div class="post-content-container">
        <h3><?= $postItem->getTitle() ?></h3>
    <?php if(!empty($postItem->getText())): ?>
        <p><?= $postItem->getText() ?></p>
    <?php else: ?>
    <?php $postImages = $image->getUploadedImages("post_id",$postId); ?>
    <?php $imgCount = count($postImages); ?>
    <div class="image" data-images='<?= json_encode($postImages) ?>' data-id="<?= $postId ?>">
        <input type="hidden" id="index-<?= $postId ?>" value="0">
        <div class="left-arrow" id="leftArrow-<?= $postId ?>">
            <img src="../images/icons/arrowLeft.png">
        </div>
        <img src="../images/uploaded/<?= $postImages[0]->getName() ?>" id="imageDisplay-<?= $postId ?>">
        <div class="right-arrow" id="rightArrow-<?= $postId ?>">
            <img src="../images/icons/arrowRight.png">
        </div>
    </div>
    <?php endif; ?>  
    </div>
    <div class="post-button-container">
        <div class="like-comment-btns">
        <div class="like-btn" id="like-post-<?= $postId ?>" data-id="<?= $postId ?>" data-type="post" data-status="<?= $postLikeStatus ?>">
        <div class="up-btn" id="up-post-<?= $postId ?>">
            <img src="../images/icons/arrow-up.png">
        </div>
        <p id="count-post-<?= $postId ?>">
            <?= $like->getLikeCount("post_id",$postId) ?></p>
        <div class="down-btn" id="down-post-<?= $postId ?>">
            <img src="../images/icons/arrow-down.png">
        </div>
        </div>
        <a href="comment.php?post_id=<?= $postId ?>" class="comment-btn">
            <img src="../images/icons/bubble.png">
            <p><?= $comment->getCommentCount("post_id",$postId); ?></p>
        </a>
        </div>
        <?php if($postItem->getUser_id() == $userId): ?>
        <div class="delete-btn">
        <img src="../images/icons/set.png">
        </div>
        <?php endif; ?>
    </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
        <div class="no-posts">
            <img src="../images/logo-not-found.png">
            <h2>There is no posts in this community yet.</h2>
        </div>
    <?php endif; ?>
</main>

    <aside class="community-info">
        <div class="info-box">
            <h3><span>r/</span><?=$selectedCommunity->getName()?></h3>
            <p class="desc"><?=$selectedCommunity->getDescription()?></p>

            <div class="created">
                <img src="../images/icons/cake.png">
                <p>Created: <?=$selectedCommunity->getTime()?></p>
            </div>
        </div>
    </aside>
</div>

</div>

<script type="module">
import {deleteCommunity} from "../script/tools.js?v=<?php echo time(); ?>";
import {likeStatus, manageLikes} from "../script/like.js?v=<?php echo time(); ?>";
import {stageImages, imageScroll} from "../script/image.js?v=<?php echo time(); ?>";
import {postSearch} from "../script/search.js?v=<?php echo time(); ?>";

likeStatus();
manageLikes();
stageImages();
imageScroll();
postSearch();
deleteCommunity();

</script>
</body>

</html>