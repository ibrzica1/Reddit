<?php

require_once "vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\services\TimeService;
use Reddit\repositories\UserRepository;
use Reddit\repositories\CommunityRepository;
use Reddit\repositories\CommentRepository;
use Reddit\repositories\PostRepository;
use Reddit\repositories\ImageRepository;
use Reddit\repositories\LikeRepository;
use Reddit\repositories\NotificationRepository;

$userRepository = new UserRepository();
$community = new CommunityRepository();
$post = new PostRepository();
$comment = new CommentRepository();
$notification = new NotificationRepository();
$session = new SessionService();
$time = new TimeService();
$image = new ImageRepository();
$like = new LikeRepository();


if(isset($_SESSION['user_id'])) {
    $id = $session->getFromSession('user_id');
    $notifications = $notification->unreadNotifications($id);
    $nottNumber = count($notifications);
} else {
    $id = 0;
}
$postCount = $post->countPosts();
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 5;
if($limit < 0) $limit = 5;
if($limit > 50) $limit = 50;
$posts = $post->getAllPosts($limit);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Index</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="style/header.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="style/index.css?v=<?php echo time(); ?>">
  
</head>

<body>
  
  <div class="header-container">
    <a class="logo-container" href="index.php">
        <img src="images/logo.png" alt="Reddit Logo" class="reddit-logo">
    </a>
    
    <div class="search-container">
        <img src="images/icons/magnifying-glass.png" alt="Search Icon" class="search-icon">
        <input type="text" placeholder="Search Reddit" id="searchInput">
        <div class="search-results" id="searchResults"></div>
    </div>
    
    <?php if($session->sessionExists("username")): ?>
      <div class="buttons-container">
    <a href="view/createPost.php" class="create-post-btn" title="Create Post">
        <img class='plus-icon' src="images/icons/plus.png">
        <p>Create</p>
    </a>
     <div class="notifications-container">
        <img src="images/icons/bell.png">
        <?php if($nottNumber > 0): ?>
            <div class="notification-number"><?= $nottNumber ?></div>
        <?php endif; ?>
    </div>
    <div class="notification-grid" id="notificatioGrid">
        
        <?php if(empty($notifications)): ?>
        <p class="empty-notification">There is no new notifications</p>
        <?php else: ?>
        <?php foreach($notifications as $notificationItem): ?>
        <?php $senderInfo = $userRepository->getUserByAttribute("id",$notificationItem->sender_id); ?>
        <?php if($notificationItem->seen == "false"): ?>
        <?php if($notificationItem->type == "like"): ?>
        <?php if(!empty($notificationItem->post_id)): ?>
        <?php $notificationPost = $post->getPostById($notificationItem->post_id) ?>
        <a href="community.php?comm_id=<?= $notificationPost->community_id ?>&nott_id=<?= $notificationItem->id ?>" 
        onclick="<?php $notification->changeSeenStatus($notificationItem->id,"true") ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="images/avatars/<?= $senderInfo->avatar ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo->username ?></span> liked your post 
            r/<span><?= $notificationPost->title ?></span></p>
        </div>  
        </a>
        <?php else: ?>
        <?php $notificationComment = $comment->getComment("id",$notificationItem->comment_id) ?>
        <a href="comment.php?post_id=<?= $notificationComment->$post_id ?>&nott_id=<?= $notificationItem->id ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="images/avatars/<?= $senderInfo->avatar ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo->username ?></span> liked your comment
            r/<span><?= $notificationComment->text ?></span></p>
        </div>
        </a>
        <?php endif; ?>
        <?php elseif($notificationItem->type == "comment"): ?>
        <?php $notificationPost = $post->getPostById($notificationItem->post_id); ?>
        <a href="comment.php?post_id=<?= $notificationPost->id ?>&nott_id=<?= $notificationItem->id ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="images/avatars/<?= $senderInfo->avatar ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo->username ?></span> commented on your post
            r/<span><?= $notificationPost->title ?></span></p>
        </div>
        </a>
        <?php elseif($notificationItem->type == "post"): ?>
        <?php $notificationCommunity = $community->getCommunity("id",$notificationItem->community_id); ?>
        <a href="community.php?comm_id=<?= $notificationCommunity->id ?>&nott_id=<?= $notificationItem->id ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="images/avatars/<?= $senderInfo->avatar ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo->username ?></span> posted in your community
            r/<span><?= $notificationCommunity->name ?></span></p>
        </div>
        </a>
        <?php else: ?>
        <?php endif; ?>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>
        <a href="view/notification.php" class="see-all-nott">see all notifications</a>
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

  <main class="posts-grid">
    <?php if(!empty($posts)): ?>
    <?php foreach($posts as $postItem): ?>
    <?php $postUser = $userRepository->getUserByAttribute("id",$postItem->user_id); ?>   
    <?php $postId = $postItem->id; ?>
    <?php $postLikes = $like->getLike("post_id",$postId,$postItem->user_id); ?>
    <?php $postLikeStatus = empty($postLikes->status) ? "neutral" : $postLikes->status ?>
    <?php $postImages = []; ?>
    <div class="post-container">
    <div class="post-user-container">
        <img src="images/avatars/<?=$postUser->avatar?>.webp">
        <p><span>u/</span><?= $postUser->username ?></p>
        <h3><?= $time->calculateTime($postItem->time); ?></h3>
    </div>
    <div class="post-content-container">
        <h3><?= $postItem->title ?></h3>
    <?php if(!empty($postItem->text)): ?>
        <p><?= $postItem->text ?></p>
    <?php else: ?>
    <?php $postImages = $image->getUploadedImages("post_id",$postId); ?>
    <?php $imgCount = count($postImages); ?>
    <div class="image" data-images='<?= json_encode($postImages) ?>' data-id="<?= $postId ?>">
        <input type="hidden" id="index-<?= $postId ?>" value="0">
        <div class="left-arrow" id="leftArrow-<?= $postId ?>">
            <img src="images/icons/arrowLeft.png">
        </div>
        <img src="images/uploaded/<?= $postImages[0]->name ?>" id="imageDisplay-<?= $postId ?>">
        <div class="right-arrow" id="rightArrow-<?= $postId ?>">
            <img src="images/icons/arrowRight.png">
        </div>
    </div>
    <?php endif; ?>  
    </div>
    <div class="post-button-container">
        <div class="like-comment-btns">
        <div class="like-btn" id="like-post-<?= $postId ?>" data-id="<?= $postId ?>" data-type="post" data-status="<?= $postLikeStatus ?>">
        <div class="up-btn" id="up-post-<?= $postId ?>">
            <img src="images/icons/arrow-up.png">
        </div>
        <p id="count-post-<?= $postId ?>">
            <?= $like->getLikeCount("post_id",$postId) ?></p>
        <div class="down-btn" id="down-post-<?= $postId ?>">
            <img src="images/icons/arrow-down.png">
        </div>
        </div>
        <a href="view/comment.php?post_id=<?= $postId ?>" class="comment-btn">
            <img src="images/icons/bubble.png">
            <p><?= $comment->getCommentCount("post_id",$postId); ?></p>
        </a>
        </div>
        <?php if($postItem->user_id == $id): ?>
        <div class="delete-btn">
        <img src="images/icons/set.png">
        </div>
        <?php endif; ?>
    </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>    
  </main>
    
  <script type="module">
    import { toggleMenu, toggleNotification, toggleSearch } from "./script/tools.js";
    import {likeStatus, manageLikes} from "./script/like.js?v=<?php echo time(); ?>";
    import {stageImages, imageScroll} from "./script/image.js?v=<?php echo time(); ?>";
    import {generalSearch} from "./script/search.js?v=<?php echo time(); ?>";
    const  menu = document.getElementById("userInfo");
    const bellIcon = document.querySelector('.notifications-container');
    const notificationNum = document.querySelector('.notification-number');

    likeStatus();
    manageLikes();
    stageImages();
    imageScroll();
    generalSearch()

    bellIcon.addEventListener('click',toggleNotification);
    menu.addEventListener('click',toggleMenu);

   

    window.addEventListener('DOMContentLoaded', () => {
        const savedScrollPos = localStorage.getItem('scrollPosition');
        if (savedScrollPos) {
            window.scrollTo(0, parseInt(savedScrollPos));
            localStorage.removeItem('scrollPosition');
        }
    });

    if(<?= $postCount ?> > <?= $limit ?> ) {
    window.addEventListener('scroll', () => {
        const scrollHeight = document.documentElement.scrollHeight;
        const scrollPos = window.innerHeight + window.scrollY;

        if(scrollPos >= scrollHeight - 50) {
            const urlParams = new URLSearchParams(window.location.search);
            let currentLimit = parseInt(urlParams.get('limit')) || 5;
            let newLimit = currentLimit + 5;

            localStorage.setItem('scrollPosition', window.scrollY);
            window.location.href = `index.php?limit=${newLimit}`;
        }
    });
};
  </script>
</body>

</html>