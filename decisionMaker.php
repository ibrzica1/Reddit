<?php

require_once "vendor/autoload.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

use Reddit\controllers\CommentController;
use Reddit\controllers\CommunityController;
use Reddit\controllers\SearchController;
use Reddit\controllers\LikeController;
use Reddit\services\SessionService;
use Reddit\services\KarmaService;
use Reddit\controllers\UserController;
use Reddit\controllers\PostController;
use Reddit\controllers\NotificationController;
use Reddit\controllers\ImageController;

$session = new SessionService();


if(isset($_POST['login']))
{
  $userController = new UserController();
  $userController->login($_POST);
}

if(isset($_POST['signup']))
{
  $userController = new UserController();
  $userController->signup($_POST);
}

if(isset($_POST['username-update']))
{
  $username = $_POST['username-update'];

  $userController = new UserController();
  $userController->changeUsername($username);
}

if(isset($_POST['email-update']))
{
  $email = $_POST['email-update'];

  $userController = new UserController();
  $userController->changeEmail($email);
}

if(isset($_POST['old-password']) 
&& isset($_POST['new-password'])
&& isset($_POST['confirm-password']))
{
  $oldPass = $_POST['old-password'];
  $newPass = $_POST['new-password'];
  $confirmPass = $_POST['confirm-password'];

  $userController = new UserController();
  $userController->changePassword($oldPass, $newPass, $confirmPass);
}

if(isset($_POST['bio-update']))
{
  $bio = $_POST['bio-update'];

  $userController = new UserController();
  $userController->changeBio($bio);
}

if(isset($_POST['avatar-update']))
{
  $avatar = $_POST['avatar-update'];

  $userController = new UserController();
  $userController->changeAvatar($avatar);
}


if(isset($_POST['create-post']))
{
  $title = $_POST['title'] ?? "";
  $communityId = (int)($_POST['community'] ?? 0);
  $text = trim($_POST['text']) ?? "";
  $images = $_FILES['image'] ?? NULL;

  $postController = new PostController();

  if(!empty($text)){
    $postController->textPost($title, $text, $communityId);
  }
  elseif(!empty($images) && 
  isset($images['error'][0]) &&
  $images['error'][0] === UPLOAD_ERR_OK){
    $postController->imagePost($title, $images, $communityId);
  }
  else{
    $message = "You didnt send text or file";
    $session->setSession("message",$message);
    header("Location: view/createPost.php");
    exit();
  }
  
}

if(isset($_POST['name']) && 
isset($_POST['description']) &&
isset($_FILES['image']))
{
  $name = $_POST['name'];
  $description = $_POST['description'];
  $image = $_FILES['image'];

  $communityController = new CommunityController();
  $communityController->createCommunity($name,$description,$image);
}

if(isset($_POST['delete-community']))
{
  $communityId = $_POST['delete-community'];

  $communityController = new CommunityController();
  $communityController->deleteCommunityController($communityId);

  header("Location: view/profile.php");
  exit();
}

if(isset($_GET['community-search']))
{
  header("Content-Type: application/json");
  
  $search = $_GET['community-search'];

  $communityController = new CommunityController();
  echo $communityController->searchCommunityConntroller($search);
}

if(isset($_GET['general-search']))
{
  header("Content-Type: application/json");
  
  $search = $_GET['general-search'];

  $searchController = new SearchController();
  $results = $searchController->allSearch($search);

  echo json_encode($results);
  exit;
}

if(isset($_GET['profile-search']) &&
isset($_GET['user-id']))
{
  header("Content-Type: application/json");
  
  $search = $_GET['profile-search'];
  $userId = $_GET['user-id'];

  $searchController = new SearchController();
  $results = $searchController->profileSearch($search,$userId);

  echo json_encode($results);
  exit;
}

if(isset($_GET['post-search']) &&
isset($_GET['comm-id']))
{
  header("Content-Type: application/json");
  
  $search = $_GET['post-search'];
  $commId = $_GET['comm-id'];

  $searchController = new SearchController();
  $results = $searchController->postSearch($search,$commId);

  echo json_encode($results);
  exit;
}

if(isset($_POST['post-like']))
{
  $postId = $_POST['post-like'];
  $userId = $session->getFromSession('user_id');

  if (empty($userId)) {
      echo json_encode([
          'status' => 'error',
          'message' => 'Not logged in'
      ]);
      exit;
  }

  $likeController = new LikeController();

  $data = $likeController->addPostLikeController($userId,$postId);
  header('Content-Type: application/json');
  echo json_encode([
      'status' => 'success',
      'new_count' => $data[0],
      'like_status' => $data[1]
  ]);
  
  exit();
}

if(isset($_POST['post-dislike']))
{
  $postId = $_POST['post-dislike'];
  $userId = $session->getFromSession('user_id');

  if (empty($userId)) {
      echo json_encode([
          'status' => 'error',
          'message' => 'Not logged in'
      ]);
      exit();
  }
  $likeController = new LikeController();

  $data = $likeController->addPostDislikeController($userId,$postId);

  header('Content-Type: application/json');
  echo json_encode([
      'status' => 'success',
      'new_count' => $data[0],
      'like_status' => $data[1]
  ]);
  exit();
}

if(isset($_POST['comment-like']))
{
  $commId = $_POST['comment-like'];
  $userId = $session->getFromSession('user_id');

  if (empty($userId)) {
      echo json_encode([
          'status' => 'error',
          'message' => 'Not logged in'
      ]);
      exit();
  }
  $likeController = new LikeController();
  

  $data = $likeController->addCommentLikeController($userId,$commId);
 
  header('Content-Type: application/json');
  echo json_encode([
      'status' => 'success',
      'new_count' => $data[0],
      'like_status' => $data[1]
  ]);
  
  exit();
}

if(isset($_POST['comment-dislike']))
{
  $postId = $_POST['comment-dislike'];
  $userId = $session->getFromSession('user_id');

  if (empty($userId)) {
      echo json_encode([
          'status' => 'error',
          'message' => 'Not logged in'
      ]);
      exit();
  }
  $likeController = new LikeController();

  $data = $likeController->addCommentDislikeController($userId,$postId);

  header('Content-Type: application/json');
  echo json_encode([
      'status' => 'success',
      'new_count' => $data[0],
      'like_status' => $data[1]
  ]);
  exit();
}

if(isset($_POST['post-delete']) &&
  isset($_POST['location']))
{
  $postId = $_POST['post-delete'];
  $location = $_POST['location'];

  $postController = new PostController();
  $postController->deletePostController($postId);

  header("Location: view/$location.php");
  exit();
}

if(isset($_POST['comment-delete']) &&
  isset($_POST['location']))
{
  $commentId = $_POST['comment-delete'];
  $location = $_POST['location'];

  $commentController = new CommentController();
  $commentController->deleteCommentController($commentId);

  header("Location: view/$location.php");
  exit();
}

if(isset($_POST['comment_text']) && isset($_POST['post_id']))
{
  $postId = $_POST['post_id'];
  $commText = $_POST['comment_text'];

  $commentController = new CommentController();
  $commentController->createComment($commText,$postId);
}

if(isset($_POST['reply-text']) && 
   isset($_POST['comment_id']) &&
   isset($_POST['post_id']))
{
  $replyText = $_POST['reply-text'];
  $commentId = $_POST['comment_id'];
  $postId = $_POST['post_id'];

  $commentController = new CommentController();
  $commentController->createReply($replyText,$commentId,$postId);
}

if(isset($_POST['mark-all-nott']))
{
  $userId = $_POST['mark-all-nott'];

  $notificationController = new NotificationController();
  $notificationController->markAllNottSeen($userId);
}

if(isset($_POST['delete-all-nott']))
{
  $userId = $_POST['delete-all-nott'];

  $notificationController = new NotificationController();
  $notificationController->deleteUserNott($userId);
}

if(isset($_POST['change-seen-nott']) &&
  isset($_POST['type']) &&
  isset($_POST['href']))
{
  $nottId = $_POST['change-seen-nott'];
  $type = $_POST['type'];
  $href = $_POST['href'];

  $notificationController = new NotificationController();
  $notificationController->markNottSeen($nottId,$type,$href);
}

if(isset($_GET['community-image']))
{
  header("Content-Type: application/json");
  $communityId = $_GET['community-image'];

  $imageController = new ImageController();
  $image = $imageController->communityImage($communityId);
  echo json_encode($image);
  exit();
}

