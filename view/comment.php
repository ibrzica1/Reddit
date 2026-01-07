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
use Reddit\repositories\NotificationRepository;

$session = new SessionService();
$time = new TimeService();
$community = new CommunityRepository();
$image = new ImageRepository();
$post = new PostRepository();
$user = new UserRepository();
$like = new LikeRepository();
$comment = new CommentRepository();
$notification = new NotificationRepository();

if(!$session->sessionExists("username"))
{
    header("Location: ../index.php");
}

$getPost = $_GET['post_id'];
if(!empty($_GET['nott_id']))
{
    $notificationId = $_GET['nott_id'];
    if(!empty($notificationId)) $notification->changeSeenStatus($notificationId,"true");
}
$postId = intval($getPost);
$selectedPost = $post->getPostById($postId);
$postCommunityId = $selectedPost->getCommunity_id();
$postCommunity = $community->getCommunity("id",$postCommunityId);
$communityImage = $image->getCommunityImage($postCommunityId);
$userId = $session->getFromSession("user_id");
$postUserId = $selectedPost->getUser_id();
$postUser = $user->getUserById($postUserId);
$userId = $session->getFromSession("user_id");
$postLikes = $like->getLike("post_id",$postId,$userId);
$likeStatus = $postLikes?->getStatus() ? "neutral" : $postLikes?->getStatus();
$comments = $comment->getComments("post_id",$postId);
$imgNum = 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment</title>
    <link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/comment.css?v=<?php echo time(); ?>">
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
            <p>r/<?= $postCommunity->getName() ?></p>
        </div>
        <input type="text" placeholder="Search in r/<?= $postCommunity->getName() ?>" id="searchInput" data-comm_id="<?= $postCommunityId ?>">
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

<div class="body-comments">
    <div class="post-container">
    <div class="post-header">
    <div class="info-header">
        <button class="get-back-btn" onclick="history.back()">
            <img src="../images/icons/back.png">
        </button>
        <div class="comm-image">
            <img src="../images/community/<?= $communityImage->getName() ?>">
        </div>
        <div class="comm-user-info">
            <div class="comm-name-time">
                <h4>r/<?= $postCommunity->getName() ?></h4>
                <p class="post-time-ago"> • <?= $time->calculateTime($selectedPost->getTime()); ?></p>
            </div>
            <div class="user-name">
                <p><?= $postUser->getUsername() ?></p>
            </div>
        </div>
    </div>
    <?php if($selectedPost->getUser_id() == $userId): ?>
        <form action="../decisionMaker.php" method="post" onsubmit="return confirm('Are you sure you want to delete this post?')">
            <input type="hidden" name="location" value="profile">
            <input type="hidden" name="post-delete" value="<?= $postId ?>">
            <button type="submit" class="delete-btn" id="delete-post-<?= $postId ?>">
                <img src="../images/icons/set.png">
            </button>
        </form>
    <?php endif; ?>
</div>
<div class="post-title">
    <h3><?= $selectedPost->getTitle() ?></h3>
</div>
<?php if(!empty($selectedPost->getText())): ?>

<div class="post-text">
    <p><?= $selectedPost->getText() ?></p>
</div>
<?php else: ?>
    <?php $postImages = $image->getUploadedImages("post_id",$postId) ?>
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
<div class="post-button-container">
<div class="like-comment-btns">
    <div class="like-btn" id="like-post-<?= $commId ?>" data-id="<?= $postId ?>" data-type="post" data-status="<?= $likeStatus ?>">
        <button class="up-btn" id="up-post-<?= $postId ?>" data-post-id="<?= $postId ?>" data-active="<?= $likeStatus == 'liked' ? 'liked' : '' ?>">
            <img src="../images/icons/arrow-up.png">
        </button>
        <p class="likes" id="count-post-<?= $postId ?>"><?= $like->getLikeCount("post_id",$postId) ?></p>
        <button class="down-btn" id="down-post-<?= $postId ?>" data-post-id="<?= $postId ?>" data-active="<?= $likeStatus == 'disliked' ? 'disliked' : '' ?>">
            <img src="../images/icons/arrow-down.png">
        </button>
    </div>
    <a href="comment.php?post_id=<?= $postId ?>" class="comment-btn">
        <img src="../images/icons/bubble.png">
        <p><?= $comment->getCommentCount("post_id",$postId) ?></p>
    </a>
</div>
</div>
</div>

<div class="reply-container">
    <p class="commenting-as">Comment as <span class="reply-username">u/<?= $session->getFromSession("username") ?></span></p>
    <form action="../decisionMaker.php" method="POST" class="comment-form">
        <input type="hidden" name="post_id" value="<?= $postId ?>">
        <textarea name="comment_text" placeholder="What are your thoughts?" rows="4"></textarea>
        <div class="form-footer">
            <button type="submit" name="submit-comment" class="comment-submit-btn">Comment</button>
        </div>
    </form>
</div>

<div class="comment-separator"></div>

<div class="comments-grid">
<?php foreach($comments as $commentItem): ?>
<?php if(empty($commentItem->getComment_id())): ?>
    <?php $commId = $commentItem->getId(); ?>
  
    <?php $commentUser = $user->getUserByAttribute("id",$commentItem->getUser_id()) ?>
    
    <?php $commentLikes = $like->getLike("comment_id",$commId,$commentItem->getUser_id())  ?>
  
    <?php $commentLikeStatus = $commentLikes?->getStatus() ? "neutral" : $commentLikes?->getStatus() ?>
    
    <div class="single-comment">
        <div class="comment-author-info">
            <img src="../images/avatars/<?= $commentUser->getAvatar() ?>.webp" class="comment-avatar">
            <span class="comment-username">u/<?= $commentUser->getUsername() ?></span>
            <span class="comment-time"><?= $time->calculateTime($commentItem->getTime()) ?></span>
        </div>
        <div class="comment-content">
            <p><?= $commentItem->getText() ?></p>
        </div>
        <div class="comment-actions">
            <div class="like-btn" id="like-comment-<?= $commId ?>" data-id="<?= $commId ?>" data-type="comment" data-status="<?= $commentLikeStatus ?>">
                <button class="up-btn" id="up-comment-<?= $commId ?>" data-comm-id="<?= $commId ?>">
                <img src="../images/icons/arrow-up.png">
                </button>
                <p class="likes" id="count-comment-<?= $commId ?>"><?= $like->getLikeCount("comment_id",$commId) ?></p>
                <button class="down-btn" id="down-comment-<?= $commId ?>" data-comm-id="<?= $commId ?>">
                <img src="../images/icons/arrow-down.png">
                </button>
            </div>
            <button class="comment-reply-btn" id="commentReplyBtn-<?= $commId ?>">
                Reply</button>
        </div>
        <form action="../decisionMaker.php" method="post" class="reply-form" id="replyForm-<?= $commId ?>" data-id="<?= $commId ?>">
            <input type="hidden" name="post_id" value="<?= $postId ?>">
            <input type="hidden" name="comment_id" value="<?= $commId ?>">
            <textarea name="reply-text" rows="1" id="replyText-<?= $commId ?>"></textarea>
            <div class="reply-btns">
                <button type="button" class="reply-cancel" id="replyCancel-<?= $commId ?>">
                    Cancel</button>
                <button type="submit" class="reply-submit" id="replySubmit-<?= $commId ?>">
                    Submit</button>
            </div>
        </form>
        <div class="reply-comments-grid">
        <?php foreach($comments as $replyItem): ?>
        <?php if($replyItem->getComment_id() === $commentItem->getId()): ?>
        <?php $replyId = $replyItem->getId(); ?>
        <?php $replyUser = $user->getUserByAttribute("id",$replyItem->getUser_id()) ?>
        <?php $replyLikes = $like->getLike("comment_id",$replyId,$replyItem->getUser_id())  ?>
        <?php $replyLikeStatus = $replyLikes?->getStatus() ? "neutral" : $replyLikes?->getStatus() ?>
            <div class="single-comment">
            <div class="comment-author-info">
                <img src="../images/avatars/<?= $replyUser->getAvatar() ?>.webp" class="comment-avatar">
                <span class="comment-username">u/<?= $replyUser->getUsername()?></span>
                <span class="comment-time"><?= $time->calculateTime($replyItem->getTime()) ?></span>
            </div>
            <div class="comment-content">
                <p><?= $replyItem->getText() ?></p>
            </div>
            <div class="comment-actions">
            <div class="like-btn" id="like-comment-<?= $replyId ?>" data-id="<?= $replyId ?>" data-type="comment" data-status="<?= $replyLikeStatus ?>">
                <button class="up-btn" id="up-comment-<?= $replyId ?>" data-comm-id="<?= $replyId ?>">
                <img src="../images/icons/arrow-up.png">
                </button>
                <p class="likes" id="count-comment-<?= $replyId ?>"><?= $like->getLikeCount("comment_id",$replyId) ?></p>
                <button class="down-btn" id="down-comment-<?= $replyId ?>" data-comm-id="<?= $replyId ?>">
                <img src="../images/icons/arrow-down.png">
                </button>
            </div>
        </div>  
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>    
    </div>
</div>

<div class="body-community">
    <div class="community-card-header">
        <h4 class="community-name">About r/<?= $postCommunity->getName() ?></h4>
    </div>
    <div class="community-description">
        <p><?= $postCommunity->getDescription() ?></p>
    </div>
    <div class="community-created">
        <p class="community-stats">
            <img src="../images/icons/cake.png" alt="Cake Day">
            Created: <?= $time->calculateTime($postCommunity->getTime()); ?>
        </p>
    </div>
    <a href="community.php?comm_id=<?= $postCommunityId ?>" class="community-view-btn">View Community</a>
</div>
</div>

<script type="module">
import {toggleReply} from "../script/tools.js?v=<?php echo time(); ?>";
import {likeStatus, manageLikes} from "../script/like.js?v=<?php echo time(); ?>";
import {stageImages, imageScroll} from "../script/image.js?v=<?php echo time(); ?>";
import {postSearch} from "../script/search.js?v=<?php echo time(); ?>";

likeStatus();
manageLikes();
stageImages();
imageScroll();
postSearch();
toggleReply();

</script>
</body>

</html>