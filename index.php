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

$user = new UserRepository();
$community = new CommunityRepository();
$post = new PostRepository();
$comment = new CommentRepository();
$notification = new NotificationRepository();
$session = new SessionService();
$time = new TimeService();
$image = new ImageRepository();
$like = new LikeRepository();


if(isset($_SESSION['user_id'])) {
    $userId = $session->getFromSession('user_id');
    $notifications = $notification->unreadNotifications($userId);
    $nottNumber = count($notifications);
} else {
    $userId = 0;
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
    <?php include __DIR__ . "/view/partials/notificationHtml.php" ?>
    <?php include __DIR__ . "/view/partials/menuHtml.php" ?>
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

  <main class="posts-grid" data-count="<?= $postCount ?>" data-limit="<?= $limit ?>">
    <?php if(!empty($posts)): ?>
    <?php foreach($posts as $postItem): ?>
    <?php $postUser = $user->getUserByAttribute("id",$postItem->getUser_id()); ?>   
    <?php $postId = $postItem->getId(); ?>
    <?php $postLikes = $like->getLike("post_id",$postId,$userId); ?>
    <?php $postCommunity = $community->getCommunity("id",$postItem->getCommunity_id()); ?>
    <?php $communityImg = $image->getCommunityImage($postCommunity->getId()); ?>
    <?php if($postLikes === NULL): ?>
    <?php $postLikeStatus = "neutral"; ?>
    <?php else: ?>
    <?php $postLikeStatus = $postLikes->getStatus(); ?>
    <?php  endif;  ?>
    <?php $postImages = []; ?>
    <div class="post-container" data-id="<?= $postId ?>">
    <div class="post-user-container">
        <a href="view/community.php?comm_id=<?= $postCommunity->getId(); ?>" 
        class="post-comm-img-container">
            <img src="images/community/<?=$communityImg->getName();?>">
        </a>
        <div class="post-info-wrapper">
          <a href="view/profile.php?tab=posts&id_user=<?=$postUser->getId()?>">
            <p><span>r/</span><?= $postUser->getUsername(); ?></p>
          </a>
          <div class="i-dont-know-what-to-name-these-blody-containers">
            <p><span>u/</span><?= $postCommunity->getName(); ?></p>
            <h3><?= $time->calculateTime($postItem->getTime()); ?></h3>
          </div>  
        </div>
    </div>
    <a href="view/comment.php?post_id=<?= $postId ?>" class="post-content-container">
        <h3><?= $postItem->getTitle() ?></h3>
        <?php if(!empty($postItem->getText())): ?>
            <p><?= $postItem->getText() ?></p>
        <?php else: ?>
        <?php $postImages = $image->getUploadedImages("post_id",$postId); ?>
        <?php $imgCount = count($postImages); ?>
        <div class="image" data-images='<?= json_encode($postImages) ?>' data-id="<?= $postId ?>">
            <input type="hidden" id="index-<?= $postId ?>" value="0">
            <div class="left-arrow" id="leftArrow-<?= $postId ?>">
                <img src="images/icons/arrowLeft.png">
            </div>
            <img src="images/uploaded/<?= $postImages[0]->getName() ?>" id="imageDisplay-<?= $postId ?>">
            <div class="right-arrow" id="rightArrow-<?= $postId ?>">
                <img src="images/icons/arrowRight.png">
            </div>
        </div>
        <?php endif; ?> 
    </a>
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
        <?php if($postItem->getUser_id() == $userId): ?>
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
    import {loadPosts} from "/Reddit/script/tools.js?v=<?php echo time(); ?>";
    import {likeStatus, manageLikes} from "/Reddit/script/like.js?v=<?php echo time(); ?>";
    import {stageImages, imageScroll} from "/Reddit/script/image.js?v=<?php echo time(); ?>";
    import {generalSearch} from "/Reddit/script/search.js?v=<?php echo time(); ?>";

    <?php if(isset($_SESSION['user_id'])): ?> 
    likeStatus(); // When page loads, like area will change color on wheter you up-voted or down-voted
    manageLikes(); // Changes the color of like area when you like or dislike
    <?php endif; ?>
    stageImages(); // If there is an image post it will display first image and if there is more then one image it will display right arrow
    imageScroll(); // Toggles through album by clicking right or left arrow, if there you reach the last picture right arrow will hide
    generalSearch(); // When you input something it will search through posts, community, users and display results
    loadPosts(); // Loads the newest 10 posts if scrolled to the end loads another 10 posts
    
  </script>
</body>

</html>