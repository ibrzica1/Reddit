<?php

require_once "vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\models\User;
use Reddit\models\Notification;
use Reddit\models\Post;
use Reddit\models\Comment;
use Reddit\models\Community;

$user = new User();
$community = new Community();
$post = new Post();
$comment = new Comment();
$notification = new Notification();
$session = new SessionService();

$id = $session->getFromSession('user_id');
$notifications = $notification->unreadNotifications($id);
$nottNumber = count($notifications);

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
        <img src="images/logo.png" alt="Reddit Logo" class="reddit-logo">
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
     <div class="notifications-container">
        <img src="images/icons/bell.png">
        <div class="notification-number"><?= $nottNumber ?></div>
    </div>
    <div class="notification-grid" id="notificatioGrid">
        
        <?php if(empty($notifications)): ?>
        <p class="empty-notification">There is no new notifications</p>
        <?php else: ?>
        <?php foreach($notifications as $notificationItem): ?>
        <?php $senderInfo = $user->getUserByAttribute("id",$notificationItem["sender_id"]); ?>
        <?php if($notificationItem["seen"] == "false"): ?>
        <?php if($notificationItem["type"] == "like"): ?>
        <?php if(!empty($notificationItem["post_id"])): ?>
        <?php $notificationPost = $post->getPost("id",$notificationItem["post_id"]) ?>
        <a href="community.php?comm_id=<?= $notificationPost[0]["community_id"] ?>&nott_id=<?= $notificationItem["id"] ?>" 
        onclick="<?php $notification->changeSeenStatus($notificationItem["id"],"true") ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="images/avatars/<?= $senderInfo["avatar"] ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo["username"] ?></span> liked your post 
            r/<span><?= $notificationPost[0]["title"] ?></span></p>
        </div>  
        </a>
        <?php else: ?>
        <?php $notificationComment = $comment->getComments("id",$notificationItem["comment_id"]) ?>
        <a href="comment.php?post_id=<?= $notificationComment[0]["post_id"] ?>&nott_id=<?= $notificationItem["id"] ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="images/avatars/<?= $senderInfo["avatar"] ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo["username"] ?></span> liked your comment
            r/<span><?= $notificationComment[0]["text"] ?></span></p>
        </div>
        </a>
        <?php endif; ?>
        <?php elseif($notificationItem["type"] == "comment"): ?>
        <?php $notificationPost = $post->getPost("id",$notificationItem["post_id"]); ?>
        <a href="comment.php?post_id=<?= $notificationPost[0]["id"] ?>&nott_id=<?= $notificationItem["id"] ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="images/avatars/<?= $senderInfo["avatar"] ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo["username"] ?></span> commented on your post
            r/<span><?= $notificationPost[0]["title"] ?></span></p>
        </div>
        </a>
        <?php elseif($notificationItem["type"] == "post"): ?>
        <?php $notificationCommunity = $community->getCommunity("id",$notificationItem["community_id"]); ?>
        <a href="community.php?comm_id=<?= $notificationCommunity[0]["id"] ?>&nott_id=<?= $notificationItem["id"] ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="images/avatars/<?= $senderInfo["avatar"] ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo["username"] ?></span> posted in your community
            r/<span><?= $notificationCommunity[0]["name"] ?></span></p>
        </div>
        </a>
        <?php else: ?>
        <?php endif; ?>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="user-info" id="userInfo">
        <div class="green-dot"></div>
        <img class="user-avatar" src="images/avatars/<?= $session->getFromSession('avatar')?>.webp">
        
    </div>
    <div class="menu-container" id="userMenu">
        <a class="profile-container" href="view/profile.php">
            <div class="avatar-container">
                <img class="user-avatar" src="images/avatars/<?= $session->getFromSession('avatar')?>.webp">
            </div>
            <div class="info-container">
                <h3>View Profile</h3>
                <p>u/<?= $session->getFromSession("username") ?></p>
            </div>
        </a>
        <a class="edit-container" href="view/editAvatar.php">
            <img src="images/icons/shirt.png">
            <p>Edit Avatar</p>
        </a>
        <a class="logout-container" href="src/controllers/Logout.php">
            <img src="images/icons/house-door.png">
            <p>Log Out</p>
        </a>
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
    import { toggleMenu, toggleNotification } from "./script/tools.js";
    const  menu = document.getElementById("userInfo");
    const bellIcon = document.querySelector('.notifications-container');
    const notificationNum = document.querySelector('.notification-number');

    bellIcon.addEventListener('click',toggleNotification);
    menu.addEventListener('click',toggleMenu);
  </script>
</body>

</html>