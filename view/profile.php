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
$user = new UserRepository();
$community = new CommunityRepository();
$image = new ImageRepository();
$post = new PostRepository();
$like = new LikeRepository();
$comment = new CommentRepository();


if(!$session->sessionExists("username"))
{
    header("Location: ../index.php");
}

$user = new UserRepository();
$userId = $session->getFromSession('user_id');
$profile = $user->getUserById($userId);

$username = $session->getFromSession("username");
$timeCreated = $profile->getTime();
$accountAge = $time->calculateTime($timeCreated[0]); 
$bio = $profile->getBio();
$karma = $profile->getKarma();
$activeTab = $_GET['tab'] ?? "posts";



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
<link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="../style/profile.css?v=<?php echo time(); ?>">
</head>

<body>

<div class="header-container">
    <a class="logo-container" href="../index.php">
        <img src="../images/logo.png" alt="Reddit Logo" class="reddit-logo">
    </a>
    
    <div class="search-container">
        <img src="../images/icons/magnifying-glass.png" alt="Search Icon" class="search-icon">
        <div class="user-search-container">
            <img src="../images/avatars/<?= $profile->getAvatar() ?>.webp">
            <p>u/<?= $profile->getUsername() ?></p>
        </div>
        <input type="text" placeholder="Search in u/<?= $profile->getUsername() ?>" id="searchInput" data-user_id="<?= $userId ?>">
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

<div class="profile-page-container">
    <div class="profile-header">
        <div class="banner"></div>
        <div class="profile-card">
            <img src="../images/avatars/<?= $session->getFromSession('avatar')?>.webp" alt="Avatar" class="profile-avatar"> 
            <div class="profile-info-content">
                <div class="username-section">
                    <h1>u/<?= $username ?></h1>
                </div>
                <a href="settings.php">
                    <button class="edit-profile-btn">Edit Profile</button>
                </a>
            </div>
            <a href="createPost.php">
                <button class="new-post-btn">Create Post</button>
            </a>
            <a href="createCommunity.php" class="new-comm-btn">
                <img src="../images/icons/add.png">
                <p>Start a Community</p>
            </a>
        </div>
    </div>

    <div class="message-container">
        <p class="message"><?=$session->displayMessage()?></p>
    </div>

<div class="content-wrapper">
<main class="main-content">
    <nav class="profile-nav">
        <a href="profile.php?tab=posts" id="posts">POSTS</a>
        <a href="profile.php?tab=comments" id="comments">COMMENTS</a>
        <a href="profile.php?tab=communities" id="communities">COMMUNITIES</a>
    </nav>
    
<div class="content-container">
    <?php if($activeTab == "communities"): ?>
        <?php $communities = $community->getCommunities("user_id",$userId); ?>
        <?php if(empty($communities)): ?>
    <div class="empty-container">
        <img src="../images/logo-not-found.png" class="logo-not-found">
        <h2>You dont have any communities yet</h2>
        <h3>Once you create a community, it'll show up here.</h3>
    </div>
        
        <?php else: ?>
        <?php foreach($communities as $community): ?>
            <?php $communityImg = $image->getCommunityImage($community->getId()); ?>
            <div class="community-card">
    <div class="community-icon">
        <img src='../images/community/<?=$communityImg->getName()?>'>
    </div>
    <div class="community-info">
        <a href="community.php?comm_id=<?=$community->getId()?>" class="community-name">
            <span>r/</span><?= $community->getName() ?></a>
        <p class="community-desc"><?= $community->getDescription() ?></p>
        <p class="community-time">Created <?= $time->calculateTime($community->getTime()); ?></p>
    </div>
    <form action="../decisionMaker.php" method="post">
        <input type="hidden" name="delete-community" value="<?=$community->getId()?>">
        <button class="delete-container" onclick='confirm("Are you sure you want do delete this community")'>
            <img src="../images/icons/set.png">
        </button>
    </form>
    
</div>
    <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>
<?php if($activeTab == "posts"): ?>
    <?php $posts = $post->getPost("user_id",$userId) ?>
    <?php if(empty($posts)): ?>
        <div class="empty-container">
            <img src="../images/logo-not-found.png" class="logo-not-found">
            <h2>You dont have any posts yet</h2>
            <h3>Once you create a post, it'll show up here.</h3>
        </div>
    <?php else: ?>
<?php foreach($posts as $postItem): ?>
    <?php $commId = $postItem->getCommunity_id(); ?>
    <?php $postCommunity = $community->getCommunity("id",$commId); ?>
    <?php $postId = $postItem->getId(); ?>
    <?php $postLikes = $like->getLike("post_id",$postId,$userId) ?>
    
    <?php $likeId = $postLikes?->getUser_id() ? 0 : $postLikes?->getUser_id(); ?>
    <?php if($postLikes === NULL): ?>
    <?php $postLikeStatus = "neutral"; ?>
    <?php else: ?>
    <?php $postLikeStatus = $postLikes->getStatus(); ?>
    <?php  endif;  ?>
    <?php $postImages = []; ?>
    
    <?php $communityImg = $image->getCommunityImage($postItem->getCommunity_id()) ?>
    <div class="post-container">
    <a href="community.php?comm_id=<?=$commId?>" class="post-user-container">
        <img src="../images/community/<?=$communityImg->getName()?>">
        <p><span>u/</span><?= $postCommunity->getName() ?></p>
        <h3><?= $time->calculateTime($postItem->getTime()); ?></h3>
    </a>
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
            <button class="up-btn" id="up-post-<?= $postId ?>" data-post-id="<?= $postId ?>">
            <img src="../images/icons/arrow-up.png">
            </button>
            <p class="likes" id="count-post-<?= $postId ?>"><?= $like->getLikeCount("post_id",$postId) ?></p>
            <button class="down-btn" id="down-post-<?= $postId ?>" data-post-id="<?= $postId ?>">
            <img src="../images/icons/arrow-down.png">
            </button>
        </div>
        <a href="comment.php?post_id=<?= $postId ?>" class="comment-btn">
            <img src="../images/icons/bubble.png">
            <p><?= $comment->getCommentCount("post_id",$postId) ?></p>
        </a>
    </div>
    <?php if($postItem->getUser_id() == $userId): ?>
    <form action="../decisionMaker.php" method="post" class="delete-post-form" data-id="<?= $postId ?>">
        <input type="hidden" name="location" value="profile">
        <input type="hidden" name="post-delete" value="<?= $postId ?>">
        <button type="submit" class="delete-btn" id="delete-post-<?= $postId ?>" 
        onclick="return confirm('Are you sure you want to delete this post?')">
        <img src="../images/icons/set.png">
        </button>
    </form>
    <?php endif; ?>
</div>
</div>
        <?php endforeach; ?>    
        <?php endif; ?>    
        <?php endif; ?> 
    <?php if($activeTab == "comments"): ?>
     <?php $comments = $comment->getComments("user_id",$userId) ?>
    <?php if(empty($comments)): ?>
        <div class="empty-container">
            <img src="../images/logo-not-found.png" class="logo-not-found">
            <h2>You dont have any comments yet</h2>
            <h3>Once you create a comment, it'll show up here.</h3>
        </div>
    <?php else: ?>   
    <?php foreach($comments as $commentItem): ?>
    <?php $commId = $commentItem->getId(); ?>
    <?php $commentUser = $user->getUserByAttribute("id",$commentItem->getUser_id()); ?>
    <?php $commentPost = $post->getPostById($commentItem->getPost_id()); ?>
    <?php $commentCommunity = $community->getCommunity("id",$commentPost->getCommunity_id()); ?>
    <?php $commentCommunityImg = $image->getCommunityImage($commentCommunity->getId()); ?>
    <?php $commentLikes = $like->getLike("comment_id",$commId,$commentItem->getUser_id());  ?>
    <?php $commentLikeStatus = $commentLikes?->getStatus() ? "neutral" : $commentLikes?->getStatus() ?>

    <div class="single-comment">
        <div class="post-info">
            <img src="../images/community/<?= $commentCommunityImg->getName() ?>" class="community-img">
            <p class="comment-community-name">r/<?= $commentCommunity->getName() ?></p>
            <p class="post-title"><?= $commentPost->getTitle() ?></p>
        </div>
        <div class="comment-user-info">
            <h3><?= $commentUser->getUsername() ?></h3>
            <p>commented <?= $time->calculateTime($commentItem->getTime()) ?></p>
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
            <a href="comment.php?post_id=<?= $commentPost->getId() ?>" class="comment-reply-btn" id="commentReplyBtn-<?= $commId ?>">
                <img src="../images/icons/bubble.png">
                <p>Reply</p>
            </a>
            
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?> 
    <?php endif; ?>   
</div>

</main>

<aside class="sidebar-right">
    <div class="sidebar-card about-user">

    <p class="display-name">
        <img src="../images/icons/cake.png" alt="Cake Day" class="cake-icon">
        User since: <?php echo $accountAge; ?>
    </p>
    <div class="karma-info">
        <img src="../images/icons/karma.png" class="karma-icon">
        <p>Karma: </p>
        <p class="karma-number"><?= number_format($karma) ?></p>
    </div>
        <h4>About User</h4>
        <p class="bio"><?= $bio ?></p>
    </div>
</aside>
</div>
</div>

<script type="module">
    import {} from "../script/tools.js?v=<?php echo time(); ?>";
    import {likeStatus, manageLikes} from "../script/like.js?v=<?php echo time(); ?>";
    import {stageImages, imageScroll} from "../script/image.js?v=<?php echo time(); ?>";
    import {profileSearch} from "../script/search.js?v=<?php echo time(); ?>";
    import {changeBanner} from "../script/avatar.js?v=<?php echo time(); ?>"; 

    likeStatus();
    manageLikes();
    stageImages();
    imageScroll();
    profileSearch();
    changeBanner('<?=$session->getFromSession('avatar')?>');
  
</script>

</body>
</html>