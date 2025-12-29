<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\repositories\UserRepository;
use Reddit\repositories\CommunityRepository;
use Reddit\repositories\CommentRepository;
use Reddit\repositories\PostRepository;
use Reddit\repositories\LikeRepository;
use Reddit\repositories\NotificationRepository;

$session = new SessionService();
$community = new CommunityRepository();
$post = new PostRepository();
$comment = new CommentRepository();
$user = new UserRepository();
$like = new LikeRepository();
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
    <img src="../images/icons/bell.png">
<?php if($nottNumber > 0): ?>
    <div class="notification-number"><?= $nottNumber ?></div>
<?php endif; ?>
</div>
<div class="notification-grid" id="notificatioGrid">
    
    <?php if(empty($notifications)): ?>
    <p class="empty-notification">There is no new notifications</p>
    <?php else: ?>
    <?php foreach($notifications as $notificationItem): ?>
    <?php $senderInfo = $user->getUserByAttribute("id",$notificationItem->sender_id); ?>
    <?php if($notificationItem->seen == "false"): ?>
    <?php if($notificationItem->type == "like"): ?>
    <?php if(!empty($notificationItem->post_id)): ?>
    <?php $notificationPost = $post->getPostById($notificationItem->post_id) ?>
    <a href="community.php?comm_id=<?= $notificationPost->community_id ?>&nott_id=<?= $notificationItem->id ?>" 
    onclick="<?php $notification->changeSeenStatus($notificationItem->id,"true") ?>" class="single-notification">
    <div class="sender-avatar">
    <img src="../images/avatars/<?= $senderInfo->avatar ?>.webp">
    </div>
    <div class="notification-body">
        <p>u/<span><?= $senderInfo->username ?></span> liked your post 
        r/<span><?= $notificationPost->title ?></span></p>
    </div>  
    </a>
    <?php else: ?>
    <?php $notificationComment = $comment->getComment("id",$notificationItem->comment_id) ?>
    <a href="comment.php?post_id=<?= $notificationComment->post_id ?>&nott_id=<?= $notificationItem->id ?>" class="single-notification">
    <div class="sender-avatar">
    <img src="../images/avatars/<?= $senderInfo->avatar ?>.webp">
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
    <img src="../images/avatars/<?= $senderInfo->avatar ?>.webp">
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
    <img src="../images/avatars/<?= $senderInfo->avatar ?>.webp">
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
    <a href="notification.php" class="see-all-nott">see all notifications</a>
</div>