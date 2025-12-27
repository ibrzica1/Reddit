<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\services\TimeService;
use Reddit\models\Post;
use Reddit\models\Notification;
use Reddit\repositories\UserRepository;
use Reddit\repositories\CommunityRepository;
use Reddit\repositories\CommentRepository;

$session = new SessionService();
$time = new TimeService();
$community = new CommunityRepository();
$post = new Post();
$user = new UserRepository();
$comment = new CommentRepository();
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
        <input type="text" placeholder="Search Reddit" id="searchInput">
        <div class="search-results" id="searchResults"></div>
    </div>
    
    <div class="buttons-container">
        <a href="../view/createPost.php" class="create-post-btn" title="Create Post">
            <img class='plus-icon' src="../images/icons/plus.png">
            <p>Create</p>
        </a>
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
        <?php $senderInfo = $user->getUserByAttribute("id",$notificationItem["sender_id"]); ?>
        <?php if($notificationItem["seen"] == "false"): ?>
        <?php if($notificationItem["type"] == "like"): ?>
        <?php if(!empty($notificationItem["post_id"])): ?>
        <?php $notificationPost = $post->getPost("id",$notificationItem["post_id"]) ?>
        <a href="community.php?comm_id=<?= $notificationPost[0]["community_id"] ?>&nott_id=<?= $notificationItem["id"] ?>" 
        onclick="<?php $notification->changeSeenStatus($notificationItem["id"],"true") ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="../images/avatars/<?= $senderInfo->avatar ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo->username ?></span> liked your post 
            r/<span><?= $notificationPost[0]["title"] ?></span></p>
        </div>  
        </a>
        <?php else: ?>
        <?php $notificationComment = $comment->getComment("id",$notificationItem["comment_id"]) ?>
        <a href="comment.php?post_id=<?= $notificationComment->post_id ?>&nott_id=<?= $notificationItem["id"] ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="../images/avatars/<?= $senderInfo->avatar ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo->username ?></span> liked your comment
            r/<span><?= $notificationComment->text ?></span></p>
        </div>
        </a>
        <?php endif; ?>
        <?php elseif($notificationItem["type"] == "comment"): ?>
        <?php $notificationPost = $post->getPost("id",$notificationItem["post_id"]); ?>
        <a href="comment.php?post_id=<?= $notificationPost[0]["id"] ?>&nott_id=<?= $notificationItem["id"] ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="../images/avatars/<?= $senderInfo->avatar ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo->username ?></span> commented on your post
            r/<span><?= $notificationPost[0]["title"] ?></span></p>
        </div>
        </a>
        <?php elseif($notificationItem["type"] == "post"): ?>
        <?php $notificationCommunity = $community->getCommunity("id",$notificationItem["community_id"]); ?>
        <a href="community.php?comm_id=<?= $notificationCommunity->id ?>&nott_id=<?= $notificationItem["id"] ?>" class="single-notification">
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
        <img src="../images/avatars/<?= $senderInf->avatar ?>.webp">
    </div>
    <div class="nott-body">
        <p>u/<span><?= $senderInf->username ?></span> liked your post </p>
        <h4><?= $notificationPos[0]["title"] ?></h4>
        <h4><?= $time->calculateTime($singleNott["time"]) ?></h4>
    </div>  
    </a>
    <?php else: ?>
    <?php $notificationCommen = $comment->getComment("id",$singleNott["comment_id"]) ?>
    <a href="comment.php?post_id=<?= $notificationCommen->post_id ?>&nott_id=<?= $singleNott["id"] ?>" class="single-nott" id="singleNot-<?= $singleNott["id"] ?>">
    <div class="sender-nott-avatar">
        <img src="../images/avatars/<?= $senderInf->avatar ?>.webp">
    </div>
    <div class="nott-body">
        <p>u/<span><?= $senderInf->username ?></span> liked your comment</p>
        <h4><?= $notificationCommen->text ?></h4>
        <h4><?= $time->calculateTime($singleNott["time"]) ?></h4>
    </div>
    </a>
    <?php endif; ?>
    <?php elseif($singleNott["type"] == "comment"): ?>
    <?php $notificationPos = $post->getPost("id",$singleNott["post_id"]); ?>
    <a href="comment.php?post_id=<?= $notificationPos[0]["id"] ?>&nott_id=<?= $singleNott["id"] ?>" class="single-nott" id="singleNot-<?= $singleNott["id"] ?>">
    <div class="sender-nott-avatar">
        <img src="../images/avatars/<?= $senderInf->avatar ?>.webp">
    </div>
    <div class="nott-body">
        <p>u/<span><?= $senderInf->username ?></span> commented on your post</p>
        <h4><?= $notificationPos[0]["title"] ?></h4>
        <h4><?= $time->calculateTime($singleNott["time"]) ?></h4>
    </div>
    </a>
    <?php elseif($singleNott["type"] == "post"): ?>
    <?php $notificationCommunit = $community->getCommunity("id",$singleNott["community_id"]); ?>
    <a href="community.php?comm_id=<?= $notificationCommunit->id ?>&nott_id=<?= $singleNott["id"] ?>" class="single-nott" id="singleNot-<?= $singleNott["id"] ?>">
    <div class="sender-nott-avatar">
        <img src="../images/avatars/<?= $senderInf->avatar ?>.webp">
    </div>
    <div class="nott-body">
        <p>u/<span><?= $senderInf->username ?></span> posted in your community</p>
        <h4><?= $notificationCommunit->name ?></h4>
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
    import { toggleMenu, toggleNotification, toggleSearch} from "../script/tools.js?v=<?php echo time(); ?>";

    const menu = document.getElementById("userInfo");
    const bellIcon = document.querySelector('.notifications-container');
    const notificationNum = document.querySelector('.notification-number');
    const searchEnter = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    menu.addEventListener('click',toggleMenu);
    bellIcon.addEventListener('click',toggleNotification);

    searchEnter.addEventListener('input', () => {
        let search = searchEnter.value.trim();
        if(search.length >= 2)
        {
            toggleSearch();
        }

        fetch("../decisionMaker.php?general-search=" + search)
        .then(res => res.json())
        .then(data => {
            searchResults.innerHTML = "";
            data.forEach(result => {
                const div = document.createElement('div');
                const divImg = document.createElement('div');
                const divInfo = document.createElement('div');
                const img = document.createElement('img');
                const h3 = document.createElement('h3');
                const p = document.createElement('p');
                const span = document.createElement('span');

                div.className = "search-result-container";
                divImg.className = "search-image-container";
                divInfo.className = "search-info-container";

                if(result['type'] === "community"){
                    h3.innerHTML = "r/" + result['display_name'];
                    p.innerHTML = result['info'];
                    fetch("../decisionMaker.php?community-image=" + result['id'])
                    .then(res => res.json())
                    .then(image => {
                        if(!image || !image.name){
                            img.src = "../images/reddit.png";
                        }
                        else{
                            img.src = "../images/community/" + image['name'];
                        }
                    });
                    div.appendChild(divImg);
                    divImg.appendChild(img);
                    div.appendChild(divInfo);
                    divInfo.appendChild(h3);
                    divInfo.appendChild(p);
                    searchResults.appendChild(div);

                    div.addEventListener("click",()=>{
                        window.location.href = "../view/community.php?comm_id=" + result['id'];
                    });
                }

                if(result['type'] === "post"){
                    h3.innerHTML = "p/" + result['display_name'];
                    p.innerHTML = result['info'];
                    img.src = "../images/reddit.png";
                    
                    div.appendChild(divImg);
                    divImg.appendChild(img);
                    div.appendChild(divInfo);
                    divInfo.appendChild(h3);
                    divInfo.appendChild(p);
                    searchResults.appendChild(div);

                    div.addEventListener("click",()=>{
                        window.location.href = "../view/community.php?comm_id=" + result['picture'];
                    });
                }
                if(result['type'] === "user"){
                    h3.innerHTML = "u/" + result['display_name'];
                    img.src = "../images/avatars/" + result['picture'] + ".webp";

                    div.appendChild(divImg);
                    divImg.appendChild(img);
                    div.appendChild(divInfo);
                    divInfo.appendChild(h3);
                    searchResults.appendChild(div);
                }
            });
        
        });
    });
</script>
</body>
</html>