<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\repositories\NotificationRepository;

$session = new SessionService();
$notificationRepo = new NotificationRepository();

$userId = $session->getFromSession('user_id');

if (!empty($_GET['nott_id'])) {
    $notificationRepo->changeSeenStatus((int) $_GET['nott_id'], "true");
}

$notifications = $notificationRepo->unreadNotifications($userId);
$nottNumber = count($notifications);

?>

<div class="notifications-container">
    <img src="/Reddit/images/icons/bell.png">
    <?php if ($nottNumber > 0): ?>
        <div class="notification-number"><?= $nottNumber ?></div>
    <?php endif; ?>
</div>

<div class="notification-grid" id="notificatioGrid">

<?php if (empty($notifications)): ?>
    <p class="empty-notification">There is no new notifications</p>
<?php else: ?>

<?php foreach ($notifications as $notification): ?>

<?php
$sender = $user->getUserByAttribute("id", $notification->getSender_id());
$type = $notification->getType();
?>

<?php if ($notification->getSeen() !== "false") continue; ?>

<?php if ($type === "like"): ?>

    <?php if ($notification->getPost_id()): ?>
        <?php $postItem = $post->getPostById($notification->getPost_id()); ?>
        <a href="/Reddit/view/community.php?comm_id=<?= $postItem->getCommunity_id() ?>&nott_id=<?= $notification->getId() ?>" class="single-notification">
            <div class="sender-avatar">
                <img src="/Reddit/images/avatars/<?= $sender->getAvatar() ?>.webp">
            </div>
            <div class="notification-body">
                <p>u/<span><?= $sender->getUsername() ?></span> liked your post r/<span><?= $postItem->getTitle() ?></span></p>
            </div>
        </a>
    <?php else: ?>
        <?php $commentItem = $comment->getComment("id", $notification->getComment_id()); ?>
        <a href="/Reddit/view/comment.php?post_id=<?= $commentItem->getPost_id() ?>&nott_id=<?= $notification->getId() ?>" class="single-notification">
            <div class="sender-avatar">
                <img src="/Reddit/images/avatars/<?= $sender->getAvatar() ?>.webp">
            </div>
            <div class="notification-body">
                <p>u/<span><?= $sender->getUsername() ?></span> liked your comment r/<span><?= $commentItem->getText() ?></span></p>
            </div>
        </a>
    <?php endif; ?>

<?php elseif ($type === "comment"): ?>

    <?php $postItem = $post->getPostById($notification->getPost_id()); ?>
    <a href="/Reddit/view/comment.php?post_id=<?= $postItem->getId() ?>&nott_id=<?= $notification->getId() ?>" class="single-notification">
        <div class="sender-avatar">
            <img src="/Reddit/images/avatars/<?= $sender->getAvatar() ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $sender->getUsername() ?></span> commented on your post r/<span><?= $postItem->getTitle() ?></span></p>
        </div>
    </a>

<?php elseif ($type === "post"): ?>

    <?php $communityItem = $community->getCommunity("id", $notification->getCommunity_id()); ?>
    <a href="/Reddit/view/community.php?comm_id=<?= $communityItem->getId() ?>&nott_id=<?= $notification->getId() ?>" class="single-notification">
        <div class="sender-avatar">
            <img src="/Reddit/images/avatars/<?= $sender->getAvatar() ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $sender->getUsername() ?></span> posted in your community r/<span><?= $communityItem->getName() ?></span></p>
        </div>
    </a>

<?php endif; ?>

<?php endforeach; ?>
<?php endif; ?>

<a href="/Reddit/view/notification.php" class="see-all-nott">see all notifications</a>

</div>

<script type="module">
import { toggleNotification } from "/Reddit/script/tools.js?v=<?= time() ?>";

const bellIcon = document.querySelector('.notifications-container');
bellIcon.addEventListener('click', toggleNotification);
</script>
