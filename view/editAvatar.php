<?php

require_once "../vendor/autoload.php";

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

if(!$session->sessionExists("username")) {
    header("Location: ../index.php");
}

$id = $session->getFromSession('user_id');
$notifications = $notification->unreadNotifications($id);
$nottNumber = count($notifications);

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
            <a class="edit-container" href="editAvatar.php">
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
    import { toggleMenu, changeBanner, toggleNotification } from "../script/tools.js?v=<?php echo time(); ?>";
    const menu = document.getElementById("userInfo");
    const avatarOptions = document.querySelectorAll(".image-wrapper");
    const avatarSelected = document.querySelector(".selected-avatar");
    const formInput = document.querySelector(".form-input");
    const bellIcon = document.querySelector('.notifications-container');
    const notificationNum = document.querySelector('.notification-number');

    changeBanner('<?=$session->getFromSession('avatar')?>');

    bellIcon.addEventListener('click',toggleNotification);
    menu.addEventListener('click', toggleMenu);

    avatarOptions.forEach(option => {
        const avatarColor = option.getAttribute('data-target');

        option.addEventListener('click',() => {
            avatarSelected.src = `../images/avatars/${avatarColor}.webp`;
            formInput.value = avatarColor;
            changeBanner(avatarColor);
        });
    });


</script>
</body>

</html>