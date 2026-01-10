<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\repositories\NotificationRepository;

$session = new SessionService();
$notification = new NotificationRepository();
if(!empty($_GET['nott_id']))
{
    $notificationId = $_GET['nott_id'];
    if(!empty($notificationId)) $notification->changeSeenStatus($notificationId,"true");
}
$notifications = $notification->unreadNotifications($userId);

$nottNumber = count($notifications);

?>

<div class="notifications-container">
    <img src="/Reddit/images/icons/bell.png">
<?php if($nottNumber > 0): ?>
    <div class="notification-number"><?= $nottNumber ?></div>
<?php endif; ?>
</div>
<div class="notification-grid" id="notificatioGrid">
    
    <?php if(empty($notifications)): ?>
    <p class="empty-notification">There is no new notifications</p>
    <?php else: ?>
        
    <?php foreach($notifications as $notificationItem): ?>
        
    <?php $senderInfo = $user->getUserByAttribute("id",$notificationItem->getSender_id()); ?>
    <?php if($notificationItem->getSeen() === "false"): ?>
    <?php if($notificationItem->getType() === "like"): ?>
    <?php if(!empty($notificationItem->getPost_id())): ?>
    <?php $notificationPost = $post->getPostById($notificationItem->getPost_id()) ?>
    <a href="community.php?comm_id=<?= $notificationPost->getCommunity_id() ?>&nott_id=<?= $notificationItem->getId() ?>" 
    class="single-notification">
    <div class="sender-avatar">
    <img src="/Reddit/images/avatars/<?= $senderInfo->getAvatar() ?>.webp">
    </div>
    <div class="notification-body">
        <p>u/<span><?= $senderInfo->getUsername() ?></span> liked your post 
        r/<span><?= $notificationPost->getTitle() ?></span></p>
    </div>  
    </a>
    <?php else: ?>
    <?php $notificationComment = $comment->getComment("id",$notificationItem->getComment_id()) ?>
    <a href="comment.php?post_id=<?= $notificationComment->getPost_id() ?>&nott_id=<?= $notificationItem->getId() ?>" class="single-notification">
    <div class="sender-avatar">
    <img src="/Reddit/images/avatars/<?= $senderInfo->getAvatar() ?>.webp">
    </div>
    <div class="notification-body">
        <p>u/<span><?= $senderInfo->getUsername() ?></span> liked your comment
        r/<span><?= $notificationComment->getText() ?></span></p>
    </div>
    </a>
    <?php endif; ?>
    <?php elseif($notificationItem->getType() == "comment"): ?>
    <?php $notificationPost = $post->getPostById($notificationItem->getPost_id()); ?>
    <a href="comment.php?post_id=<?= $notificationPost->getId() ?>&nott_id=<?= $notificationItem->getId() ?>" class="single-notification">
    <div class="sender-avatar">
    <img src="/Reddit/images/avatars/<?= $senderInfo->getAvatar() ?>.webp">
    </div>
    <div class="notification-body">
        <p>u/<span><?= $senderInfo->getUsername() ?></span> commented on your post
        r/<span><?= $notificationPost->getTitle() ?></span></p>
    </div>
    </a>
    <?php elseif($notificationItem->getType() == "post"): ?>
    <?php $notificationCommunity = $community->getCommunity("id",$notificationItem->getCommunity_id()); ?>
    <a href="community.php?comm_id=<?= $notificationCommunity->getId() ?>&nott_id=<?= $notificationItem->getId() ?>" class="single-notification">
    <div class="sender-avatar">
    <img src="/Reddit/images/avatars/<?= $senderInfo->getAvatar() ?>.webp">
    </div>
    <div class="notification-body">
        <p>u/<span><?= $senderInfo->getUsername() ?></span> posted in your community
        r/<span><?= $notificationCommunity->getName() ?></span></p>
    </div>
    </a>
    <?php else: ?>
    <?php endif; ?>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
    <a href="/Reddit/view/notification.php" class="see-all-nott">see all notifications</a>
</div>

<script type="module">
    import { toggleNotification } from "/Reddit/script/tools.js?v=<?php echo time(); ?>";
    const bellIcon = document.querySelector('.notifications-container');

    bellIcon.addEventListener('click',toggleNotification);
</script>