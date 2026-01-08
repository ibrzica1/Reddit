<?php

namespace Reddit\controllers;

use Reddit\models\Comment;
use Reddit\services\SessionService;
use Reddit\services\TimeService;
use Reddit\controllers\NotificationController;
use Reddit\repositories\CommentRepository;
use Reddit\repositories\LikeRepository;

class CommentController extends CommentRepository
{
    public function createReply($text,$commentId,$postId)
    {
        $session = new SessionService();
        $timeStamp = new TimeService();
        $notificationController = new NotificationController();

        if(!isset($text))
        {
        $message = "You didnt send comment text";
        $session->setSession("message",$message);
        header("Location: view/comment.php?post_id=$postId");
        exit();
        }

        if(!isset($commentId))
        {
        $message = "You didnt send comment id";
        $session->setSession("message",$message);
        header("Location: view/comment.php?post_id=$postId");
        exit();
        }

        if(!isset($postId))
        {
        $message = "You didnt send comment id";
        $session->setSession("message",$message);
        header("Location: index.php");
        exit();
        }

        if(!Comment::commentLength($text))
        {
          $message = "Text cant be longer than 500 letters";
          $session->setSession("message",$message);
          header("Location: view/comment.php?post_id=$postId");
          exit();  
        }

        $userId = $session->getFromSession("user_id");
        $time = $timeStamp->time;

        $newComment = new Comment([
          'id' => NULL,
          'text' => $text,
          'user_id' => $userId,
          'post_id' => $postId,
          'comment_id' => $commentId,
          'time' => $time
        ]);

        $this->registerReply($newComment);
        $commentId = $this->connection->lastInsertId();
        $notificationController->commentNotification($userId,$commentId,$postId,$time);

        header("Location: view/comment.php?post_id=$postId");
    }

    public function createComment($text,$postId)
    {
        $session = new SessionService();
        $timeStamp = new TimeService();
        $notificationController = new NotificationController();

        if(!isset($text))
        {
        $message = "You didnt send comment text";
        $session->setSession("message",$message);
        header("Location: view/comment.php?post_id=$postId");
        exit();
        }

        if(!isset($postId))
        {
        $message = "You didnt send post id";
        $session->setSession("message",$message);
        header("Location: view/comment.php?post_id=$postId");
        exit();
        }

        if(!Comment::commentLength($text))
        {
          $message = "Text cant be longer than 500 letters";
          $session->setSession("message",$message);
          header("Location: view/comment.php?post_id=$postId");
          exit();  
        }

        $userId = $session->getFromSession("user_id");
        $time = $timeStamp->time;

        $newComment = new Comment([
          'id' => NULL,
          'text' => $text,
          'user_id' => $userId,
          'post_id' => $postId,
          'comment_id' => NULL,
          'time' => $time
        ]);

        $this->registerComment($newComment);
        $commentId = $this->connection->lastInsertId();
        $notificationController->commentNotification($userId,$commentId,$postId,$time);

        header("Location: view/comment.php?post_id=$postId");

    }

    public function deleteCommentController($commentId)
    {
        $session = new SessionService();
        $like = new LikeRepository();
        $likeController = new LikeController();
        $notificationController = new NotificationController();

        if(!isset($commentId))
        {
        $message = "You didnt send comment Id";
        $session->setSession("message",$message);
        header("Location: view/index.php");
        exit();
        }

        $this->deleteComment($commentId);
        $commentLikes = $like->getCommentLikes($commentId);

        if(!empty($commentLikes))
        {
          foreach($commentLikes as $commentLike)
          {
            $likeId = $commentLike->getId();
            $likeController->deleteLikeController($likeId);
          }
        }
        $notificationController->deleteCommentNott($commentId);
    }

}