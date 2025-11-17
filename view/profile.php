<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\services\TimeService;
use Reddit\models\User;
use Reddit\models\Community;
$session = new SessionService();
$time = new TimeService();
$community = new Community();

if(!$session->sessionExists("username"))
{
    header("Location: ../index.php");
}

$user = new User();

$id = $session->getFromSession('user_id');
$username = $session->getFromSession("username");
$timeCreated = $user->getUserAtribute('time',$id);
$userKarma = $user->getUserAtribute('karma',$id);
$accountAge = $time->calculateTime($timeCreated[0]); 
$bio = $user->getUserAtribute('bio',$id);
$karma = $userKarma[0];
$activeTab = $_GET['tab'] ?? 'posts';

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
        <img src="../images/reddit.png" alt="Reddit Logo" class="reddit-logo">
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
            <a class="notifications-container" href="">
                <img src="../images/icons/bell.png">
            </a>
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

<div class="profile-page-container">
    <div class="profile-header">
        <div class="banner"></div>
        <div class="profile-card">
            <img src="../images/avatars/<?= $session->getFromSession('avatar')?>.webp" alt="Avatar" class="profile-avatar"> 
            
            <div class="profile-info-content">
                <div class="username-section">
                    <h1>u/<?= $username ?></h1>
                    <p class="display-name">
                        <img src="../images/icons/cake.png" alt="Cake Day" class="cake-icon">
                        User since: <?php echo $accountAge; ?>
                    </p>
                </div>
                <a href="settings.php">
                    <button class="edit-profile-btn">Edit Profile</button>
                </a>
                
            </div>
            
            <p class="bio"><?= $bio[0] ?></p>
            
            <div class="karma-info">
                <div>
                    <strong>KARMA</strong>
                    <p><?= number_format($karma) ?></p>
                </div>
            </div>
            <a href="createPost.php">
                <button class="new-post-btn">Create Post</button>
            </a>
            <a href="createCommunity.php" class="new-comm-btn">
                <img src="../images/icons/plus.png">
                <p>Start a Community</p>
            </a>
            
        </div>
    </div>

    <div class="content-wrapper">
        <main class="main-content">
            <nav class="profile-nav">
                <a href="#" class="active">POSTS</a>
                <a href="#">COMMENTS</a>
                <a href="profile.php?tab=communities">COMMUNITIES</a>
            </nav>
            
            <div class="content-container">
                <?php if($activeTab == "communities"): ?>
                    <?php $communities = $community->getCommunity($id); ?>
                    <?php foreach($communities as $community): ?>
                       <div class="community-card">
                <div class="community-icon">r/<?= strtoupper(substr($community['name'], 0, 1)); ?></div>
                <div class="community-info">
                    <p class="community-name">r/<?= $community['name'] ?></p>
                    <p class="community-desc"><?= $community['description'] ?></p>
                    <p class="community-time">Created <?= $time->calculateTime($community['time']); ?></p>
                </div>
                <button class="join-btn">Join</button>
            </div>
                    <?php endforeach; ?>
                <?php elseif($activeTab == "comments"): ?>
                <?php else: ?>
                <?php endif; ?>    
            </div>

        </main>

        <aside class="sidebar-right">
            <div class="sidebar-card about-user">
                <h4>About User</h4>
                <p>This is a short, public note about the user. The user is verified and active.</p>
                <a href="#">Add social links</a>
            </div>
            <div class="sidebar-card rules">
                <h4>Rules and Info</h4>
                <ul>
                    <li><a href="#">Code of Conduct</a></li>
                    <li><a href="#">Content Submission</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
        </aside>
    </div>
</div>
<script type="module">
    import { toggleMenu, changeBanner} from "../script/tools.js?v=<?php echo time(); ?>";
    const  menu = document.getElementById("userInfo");
    menu.addEventListener('click',toggleMenu);

    changeBanner('<?=$session->getFromSession('avatar')?>');
</script>

</body>
</html>