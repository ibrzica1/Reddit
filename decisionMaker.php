<?php

require_once "vendor/autoload.php";

use Reddit\controllers\CommentController;
use Reddit\controllers\CommunityController;
use Reddit\controllers\LikeController;
use Reddit\services\SessionService;
use Reddit\controllers\UserController;
use Reddit\controllers\PostController;
use Reddit\controllers\NotificationController;

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

if(isset($_POST['title']) && 
isset($_POST['community']) &&
!empty(trim($_POST['text'])))
{
  $title = $_POST['title'];
  $text = $_POST['text'];
  $communityId = $_POST['community'];

  $postController = new PostController();
  $postController->textPost($title, $text, $communityId);
}

if(isset($_POST['title']) && 
isset($_POST['community']) &&
!empty($_FILES['image']['name'][0]))
{
  $title = $_POST['title'];
  $images = $_FILES['image'];
  $communityId = $_POST['community'];

  $postController = new PostController();
  $postController->imagePost($title, $images, $communityId);
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
}

if(isset($_GET['community-search']))
{
  header("Content-Type: application/json");
  
  $search = $_GET['community-search'];

  $communityController = new CommunityController();
  echo $communityController->searchCommunityConntroller($search);

}

if(isset($_POST['post-like']))
{
  $postId = $_POST['post-like'];
  $userId = $session->getFromSession('user_id');

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

if(isset($_POST['post-delete']))
{
  $postId = $_POST['post-delete'];
  $location = $_POST['location'];

  $postController = new PostController();
  $postController->deletePostController($postId,$location);

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