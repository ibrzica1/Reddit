<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\services\TimeService;
use Reddit\repositories\UserRepository;
use Reddit\repositories\CommunityRepository;
use Reddit\repositories\CommentRepository;
use Reddit\repositories\PostRepository;
use Reddit\repositories\NotificationRepository;

$session = new SessionService();
$time = new TimeService();
$community = new CommunityRepository();
$post = new PostRepository();
$user = new UserRepository();
$comment = new CommentRepository();
$notification = new NotificationRepository();

$userId = $session->getFromSession("user_id");
$notifications = $notification->unreadNotifications($userId);
$nottNumber = count($notifications);
$allNotifications = $notification->getUserNotifications($userId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>
    <link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/notification.css?v=<?php echo time(); ?>">
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

<h2 class="nott-title">Notifications</h2>

<div class="options-container">
    <form action="../decisionMaker.php" method="post">
        <input type="hidden" name="mark-all-nott" value="<?= $userId ?>">
        <button type="submit" class="mark-all">Mark all as read</button>
    </form>
    <form action="../decisionMaker.php" method="post" onsubmit="return confirm('Are you sure you want to delete all notifications?')">
        <input type="hidden" name="delete-all-nott" value="<?= $userId ?>">
        <button type="submit" class="delete-all">Delete all notifications</button>
    </form>
</div>


<div class="nott-grid">
    <?php if(empty($allNotifications)): ?>
    <p class="empty-nottt">There is no new notifications</p>
    <?php else: ?>
    <?php foreach($allNotifications as $singleNott): ?>
    <?php $senderInf = $user->getUserByAttribute("id",$singleNott->getSender_id()); ?>
    <?php if($singleNott->getType() == "like"): ?>
    <?php if(!empty($singleNott->getPost_id())): ?>
    <?php $notificationPos = $post->getPostById($singleNott->getPost_id()) ?>
    <a href="community.php?comm_id=<?= $notificationPos->getCommunity_id() ?>&nott_id=<?= $singleNott->getId() ?>" 
    onclick="<?php $notification->changeSeenStatus($singleNott->getId(),"true") ?>" class="single-nott" id="singleNot-<?= $singleNott->getId() ?>">
    <div class="sender-nott-avatar">
        <img src="../images/avatars/<?= $senderInf->getAvatar() ?>.webp">
    </div>
    <div class="nott-body">
        <p>u/<span><?= $senderInf->getUsername() ?></span> liked your post </p>
        <h4><?= $notificationPos->getTitle() ?></h4>
        <h4><?= $time->calculateTime($singleNott->getTime()) ?></h4>
    </div>  
    </a>
    <?php else: ?>
    <?php $notificationCommen = $comment->getComment("id",$singleNott->getComment_id()) ?>
    <a href="comment.php?post_id=<?= $notificationCommen->getPost_id() ?>&nott_id=<?= $singleNott->getId() ?>" class="single-nott" id="singleNot-<?= $singleNott->getId() ?>">
    <div class="sender-nott-avatar">
        <img src="../images/avatars/<?= $senderInf->getAvatar() ?>.webp">
    </div>
    <div class="nott-body">
        <p>u/<span><?= $senderInf->getUsername() ?></span> liked your comment</p>
        <h4><?= $notificationCommen->getText() ?></h4>
        <h4><?= $time->calculateTime($singleNott->getTime()) ?></h4>
    </div>
    </a>
    <?php endif; ?>
    <?php elseif($singleNott->getType() == "comment"): ?>
    <?php $notificationPos = $post->getPostById($singleNott->getPost_id()); ?>
    <a href="comment.php?post_id=<?= $notificationPos->getId()?>&nott_id=<?= $singleNott->getId() ?>" class="single-nott" id="singleNot-<?= $singleNott->getId() ?>">
    <div class="sender-nott-avatar">
        <img src="../images/avatars/<?= $senderInf->getAvatar() ?>.webp">
    </div>
    <div class="nott-body">
        <p>u/<span><?= $senderInf->getUsername() ?></span> commented on your post</p>
        <h4><?= $notificationPos->getTitle()?></h4>
        <h4><?= $time->calculateTime($singleNott->getTime()) ?></h4>
    </div>
    </a>
    <?php elseif($singleNott->getType() == "post"): ?>
    <?php $notificationCommunit = $community->getCommunity("id",$singleNott->getCommunity_id()); ?>
    <a href="community.php?comm_id=<?= $notificationCommunit->getId() ?>&nott_id=<?= $singleNott->getId() ?>" class="single-nott" id="singleNot-<?= $singleNott->getId() ?>">
    <div class="sender-nott-avatar">
        <img src="../images/avatars/<?= $senderInf->getAvatar() ?>.webp">
    </div>
    <div class="nott-body"> 
        <p>u/<span><?= $senderInf->getUsername() ?></span> posted in your community</p>
        <h4><?= $notificationCommunit->getName() ?></h4>
        <h4><?= $time->calculateTime($singleNott->getTime()) ?></h4>
    </div>
    </a>
    <?php else: ?>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

</div>
    
<script type="module">
    import {toggleNotification} from "../script/tools.js?v=<?php echo time(); ?>";
    import {generalSearch} from "../script/search.js?v=<?php echo time(); ?>";  
    
    generalSearch();

</script>
</body>
</html>