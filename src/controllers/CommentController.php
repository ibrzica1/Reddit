<?php

namespace Reddit\controllers;

use Reddit\models\Comment;
use Reddit\services\SessionService;
use Reddit\services\TimeService;
use Reddit\controllers\NotificationController;

class CommentController extends Comment
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

        if(!$this->commentLength($text))
        {
          $message = "Text cant be longer than 500 letters";
          $session->setSession("message",$message);
          header("Location: view/comment.php?post_id=$postId");
          exit();  
        }

        $userId = $session->getFromSession("user_id");
        $time = $timeStamp->time;

        $this->registerReply($text,$userId,$postId,$commentId,$time);
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

        if(!$this->commentLength($text))
        {
          $message = "Text cant be longer than 500 letters";
          $session->setSession("message",$message);
          header("Location: view/comment.php?post_id=$postId");
          exit();  
        }

        $userId = $session->getFromSession("user_id");
        $time = $timeStamp->time;

        $this->registerComment($text,$userId,$postId,$time);
        $commentId = $this->connection->lastInsertId();
        $notificationController->commentNotification($userId,$commentId,$postId,$time);

        header("Location: view/comment.php?post_id=$postId");

    }

}