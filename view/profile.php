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
$timeCreated = $user->getUserAtribute('time',$userId);
$accountAge = $time->calculateTime($timeCreated[0]); 
$bio = $profile->bio;
$karma = $profile->karma;
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
            <img src="../images/avatars/<?= $profile->avatar ?>.webp">
            <p>u/<?= $profile->username ?></p>
        </div>
        <input type="text" placeholder="Search in u/<?= $profile->username ?>" id="searchInput" data-user_id="<?= $userId ?>">
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
            <?php $communityImg = $image->getCommunityImage($community->id); ?>
            <div class="community-card">
    <div class="community-icon">
        <img src='../images/community/<?=$communityImg->name?>' alt="">
    </div>
    <div class="community-info">
        <a href="community.php?comm_id=<?=$community->id?>" class="community-name">
            <span>r/</span><?= $community->name ?></a>
        <p class="community-desc"><?= $community->description ?></p>
        <p class="community-time">Created <?= $time->calculateTime($community->time); ?></p>
    </div>
    <form action="../decisionMaker.php" method="post">
        <input type="hidden" name="delete-community" value="<?=$community->id?>">
        <button class="delete-container">
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
    <?php $commId = $postItem->community_id; ?>
    <?php $postCommunity = $community->getCommunity("id",$commId); ?>
    <?php $postId = $postItem->id ?>
    <?php $postLikes = $like->getLike("post_id",$postId,$userId) ?>
    <?php $likeId = empty($postLikes->user_id) ? 0 : $postLikes->user_id; ?>
    <?php $likeStatus = empty($postLikes->status) ? "neutral" : $postLikes->status ?>
    <?php $postImages = []; ?>
    
    <?php $communityImg = $image->getCommunityImage($postItem->community_id) ?>
    <div class="post-container">
    <a href="community.php?comm_id=<?=$commId?>" class="post-user-container">
        <img src="../images/community/<?=$communityImg->name?>">
        <p><span>u/</span><?= $postCommunity->name ?></p>
        <h3><?= $time->calculateTime($postItem->time); ?></h3>
    </a>
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
                <img src="../images/icons/arrowLeft.png">
            </div>
            <img src="../images/uploaded/<?= $postImages[0]->name ?>" id="imageDisplay-<?= $postId ?>">
            <div class="right-arrow" id="rightArrow-<?= $postId ?>">
                <img src="../images/icons/arrowRight.png">
            </div>
        </div>
    <?php endif; ?>
    </div>
    <div class="post-button-container">
    <div class="like-comment-btns">
        <div class="like-btn" id="like-post-<?= $postId ?>" data-id="<?= $postId ?>" data-type="post" data-status="<?= $likeStatus ?>">
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
    <?php if($postItem->user_id == $userId): ?>
    <form action="../decisionMaker.php" method="post">
        <input type="hidden" name="location" value="profile">
        <input type="hidden" name="post-delete" value="<?= $postId ?>">
        <button type="submit" class="delete-btn" id="delete-post-<?= $postId ?>">
        <img src="../images/icons/set.png">
        </button>
    </form>
    <?php endif; ?>
</div>
</div>
<script>
{
const idPost = <?= $postId ?>;
const likeCount = document.getElementById(`count-${idPost}`);
const likeContainer = document.getElementById(`like-${idPost}`);
const upBtn = document.getElementById(`up-${idPost}`);
const downBtn = document.getElementById(`down-${idPost}`);
const deletePost = document.getElementById(`delete-post-${idPost}`);


if(<?= $likeId ?> === <?= $userId ?> && "<?= $likeStatus ?>" === "liked")
{
    likeContainer.style.backgroundColor = "rgba(223, 120, 120, 1)";
    upBtn.style.backgroundColor = "rgba(220, 55, 55, 1)";
    downBtn.style.backgroundColor = "rgba(223, 120, 120, 1)";
}
if(<?= $likeId ?> === <?= $userId ?> && "<?= $likeStatus ?>" === "disliked")
{
    likeContainer.style.backgroundColor = "rgba(112, 148, 220, 1)";
    upBtn.style.backgroundColor = "rgba(112, 148, 220, 1)";
    downBtn.style.backgroundColor = "rgba(66, 117, 220, 1)";
}

const handleLike = (liketype)=>{
                
fetch('../decisionMaker.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `post-${liketype}=${idPost}` 
})
.then(response => response.json())
.then(data => {
    if(data.status === "success") {
        let count = data.new_count < 0 ? 0 : data.new_count;
        likeCount.textContent = count;
        const status = data.like_status; 

    if (status === "liked") {
        likeContainer.style.backgroundColor = "rgba(223, 120, 120, 1)";
        upBtn.style.backgroundColor = "rgba(220, 55, 55, 1)";
        downBtn.style.backgroundColor = "rgba(223, 120, 120, 1)";
    } else if (status === "disliked") {
        likeContainer.style.backgroundColor = "rgba(112, 148, 220, 1)";
        upBtn.style.backgroundColor = "rgba(112, 148, 220, 1)";
        downBtn.style.backgroundColor = "rgba(66, 117, 220, 1)";
    } else { 
        likeContainer.style.backgroundColor = "#dee8fe";
        upBtn.style.backgroundColor = "#dee8fe";
        downBtn.style.backgroundColor = "#dee8fe";
    }
    }})
    .catch(error => console.error('Network error:', error));
    };

    upBtn.addEventListener('click', () => handleLike('like'));
    downBtn.addEventListener('click', () => handleLike('dislike'));
    deletePost.addEventListener('click',()=>{
    if(confirm("Are you sure you want do delete this post"))
    {
        deletePost.disabled = false;
    }
    });
    }
</script>
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
    <?php $commId = $commentItem->id; ?>
    <?php $commentUser = $user->getUserByAttribute("id",$commentItem->user_id); ?>
    <?php $commentPost = $post->getPostById($commentItem->post_id); ?>
    <?php $commentCommunity = $community->getCommunity("id",$commentPost->community_id); ?>
    <?php $commentCommunityImg = $image->getCommunityImage($commentCommunity->id); ?>
    <?php $commentLikes = $like->getLike("comment_id",$commId,$commentItem->user_id);  ?>
    <?php $commentLikeStatus = empty($commentLikes->status) ? "neutral" : $commentLikes->status ?>

    <div class="single-comment">
        <div class="post-info">
            <img src="../images/community/<?= $commentCommunityImg->name ?>" class="community-img">
            <p class="comment-community-name">r/<?= $commentCommunity->name ?></p>
            <p class="post-title"><?= $commentPost->title ?></p>
        </div>
        <div class="comment-user-info">
            <h3><?= $commentUser->username ?></h3>
            <p>commented <?= $time->calculateTime($commentItem->time) ?></p>
        </div>
        <div class="comment-content">
            <p><?= $commentItem->text ?></p>
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
            <a href="comment.php?post_id=<?= $commentPost->id ?>" class="comment-reply-btn" id="commentReplyBtn-<?= $commId ?>">
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
    import {changeBanner, toggleNotification} from "../script/tools.js?v=<?php echo time(); ?>";
    import {likeStatus, manageLikes} from "../script/like.js?v=<?php echo time(); ?>";
    import {stageImages, imageScroll} from "../script/image.js?v=<?php echo time(); ?>";
    import {profileSearch} from "../script/search.js?v=<?php echo time(); ?>";
    const userId = <?= $userId ?>;
    const postBtn = document.getElementById("posts");
    const communityBtn = document.getElementById("communities");
    const commentsBtn = document.getElementById("comments");
    const deleteBtn = document.querySelectorAll('.delete-container');
    const bellIcon = document.querySelector('.notifications-container');
    const notificationNum = document.querySelector('.notification-number');

    likeStatus();
    manageLikes();
    stageImages();
    imageScroll();
    profileSearch()
    
    deleteBtn.forEach(btn => {
        btn.addEventListener('click',()=>{
            if(confirm("Are you sure you want do delete this community"))
            {
                btn.disabled = false;
            }
        });
    });

    bellIcon.addEventListener('click',toggleNotification);

    "<?=$activeTab?>" == "posts" && postBtn.classList.add("active");
        
    "<?=$activeTab?>" == "communities" && communityBtn.classList.add("active");
    
    "<?=$activeTab?>" == "comments" && commentsBtn.classList.add("active");

    changeBanner('<?=$session->getFromSession('avatar')?>');

   
</script>

</body>
</html>