<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\services\TimeService;
use Reddit\models\User;
use Reddit\models\Community;
use Reddit\models\Post;
use Reddit\models\Comment;
use Reddit\models\Notification;

$session = new SessionService();
$time = new TimeService();
$community = new Community();
$post = new Post();
$user = new User();
$comment = new Comment();
$notification = new Notification();

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
        <input type="text" placeholder="Search Reddit">
    </div>
    
    <div class="buttons-container">
        <a href="../view/createPost.php" class="create-post-btn" title="Create Post">
            <img class='plus-icon' src="../images/icons/plus.png">
            <p>Create</p>
        </a>
        <div class="notifications-container">
            <img src="../images/icons/bell.png">
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
           <img src="../images/avatars/<?= $senderInfo["avatar"] ?>.webp">
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
           <img src="../images/avatars/<?= $senderInfo["avatar"] ?>.webp">
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
           <img src="../images/avatars/<?= $senderInfo["avatar"] ?>.webp">
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
           <img src="../images/avatars/<?= $senderInfo["avatar"] ?>.webp">
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
    <?php $senderInf = $user->getUserByAttribute("id",$singleNott["sender_id"]); ?>
    <?php if($singleNott["type"] == "like"): ?>
    <?php if(!empty($singleNott["post_id"])): ?>
    <?php $notificationPos = $post->getPost("id",$singleNott["post_id"]) ?>
    <a href="community.php?comm_id=<?= $notificationPos[0]["community_id"] ?>&nott_id=<?= $singleNott["id"] ?>" 
    onclick="<?php $notification->changeSeenStatus($singleNott["id"],"true") ?>" class="single-nott" id="singleNot-<?= $singleNott["id"] ?>">
    <div class="sender-nott-avatar">
        <img src="../images/avatars/<?= $senderInf["avatar"] ?>.webp">
    </div>
    <div class="nott-body">
        <p>u/<span><?= $senderInf["username"] ?></span> liked your post </p>
        <h4><?= $notificationPos[0]["title"] ?></h4>
        <h4><?= $time->calculateTime($singleNott["time"]) ?></h4>
    </div>  
    </a>
    <?php else: ?>
    <?php $notificationCommen = $comment->getComments("id",$singleNott["comment_id"]) ?>
    <a href="comment.php?post_id=<?= $notificationCommen[0]["post_id"] ?>&nott_id=<?= $singleNott["id"] ?>" class="single-nott" id="singleNot-<?= $singleNott["id"] ?>">
    <div class="sender-nott-avatar">
        <img src="../images/avatars/<?= $senderInf["avatar"] ?>.webp">
    </div>
    <div class="nott-body">
        <p>u/<span><?= $senderInf["username"] ?></span> liked your comment</p>
        <h4><?= $notificationCommen[0]["text"] ?></h4>
        <h4><?= $time->calculateTime($singleNott["time"]) ?></h4>
    </div>
    </a>
    <?php endif; ?>
    <?php elseif($singleNott["type"] == "comment"): ?>
    <?php $notificationPos = $post->getPost("id",$singleNott["post_id"]); ?>
    <a href="comment.php?post_id=<?= $notificationPos[0]["id"] ?>&nott_id=<?= $singleNott["id"] ?>" class="single-nott" id="singleNot-<?= $singleNott["id"] ?>">
    <div class="sender-nott-avatar">
        <img src="../images/avatars/<?= $senderInf["avatar"] ?>.webp">
    </div>
    <div class="nott-body">
        <p>u/<span><?= $senderInf["username"] ?></span> commented on your post</p>
        <h4><?= $notificationPos[0]["title"] ?></h4>
        <h4><?= $time->calculateTime($singleNott["time"]) ?></h4>
    </div>
    </a>
    <?php elseif($singleNott["type"] == "post"): ?>
    <?php $notificationCommunit = $community->getCommunity("id",$singleNott["community_id"]); ?>
    <a href="community.php?comm_id=<?= $notificationCommunit[0]["id"] ?>&nott_id=<?= $singleNott["id"] ?>" class="single-nott" id="singleNot-<?= $singleNott["id"] ?>">
    <div class="sender-nott-avatar">
        <img src="../images/avatars/<?= $senderInf["avatar"] ?>.webp">
    </div>
    <div class="nott-body">
        <p>u/<span><?= $senderInf["username"] ?></span> posted in your community</p>
        <h4><?= $notificationCommunit[0]["name"] ?></h4>
        <h4><?= $time->calculateTime($singleNott["time"]) ?></h4>
    </div>
    </a>
    <?php else: ?>
    <?php endif; ?>
<script>
    {
        const nottId = <?= $singleNott["id"] ?>;
        const notification = document.getElementById(`singleNot-${nottId}`);

        if("<?= $singleNott['seen'] ?>" === "false"){
            notification.style.backgroundColor = "rgba(235, 235, 235, 1)";
        }
    }
</script>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

</div>
    
<script type="module">
    import { toggleMenu, toggleNotification} from "../script/tools.js?v=<?php echo time(); ?>";

    const menu = document.getElementById("userInfo");
    const bellIcon = document.querySelector('.notifications-container');
    const notificationNum = document.querySelector('.notification-number');

    menu.addEventListener('click',toggleMenu);
    bellIcon.addEventListener('click',toggleNotification);
</script>
</body>
</html>