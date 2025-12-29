<?php

namespace Reddit\controllers;

use Reddit\models\Notification;
use Reddit\services\TimeService;
use Reddit\repositories\CommunityRepository;
use Reddit\repositories\CommentRepository;
use Reddit\repositories\PostRepository;
use Reddit\repositories\NotificationRepository;


class NotificationController extends NotificationRepository
{
    public function likeCommentNotification($senderId,$likeId,$commentId)
    {
        $timeStamp = new TimeService();
        $comment = new CommentRepository();

        $selectedComment = $comment->getComment("id",$commentId);
        $recieverId = $selectedComment->user_id;
        $time = $timeStamp->time;

        if($this->existNotification($recieverId,"like","comment_id",$commentId))
        {
            return;
        }

        if($recieverId == $senderId || empty($recieverId))
        {
            return;
        }

        $type = "like";
        $seen = "false";

        $newNotification = new Notification([
            'id' => NULL,
            'reciever_id' => $recieverId,
            'sender_id' => $senderId,
            'like_id' => $likeId,
            'comment_id' => $commentId,
            'post_id' => NULL,
            'community_id' => NULL,
            'type' => $type,
            'seen' => $seen,
            'time' => $time
        ]);

        $this->registerLikeCommentNotification($newNotification);
    }

    public function likePostNotification($senderId,$likeId,$postId)
    {
        $timeStamp = new TimeService();
        $post = new PostRepository();

        $selectedPost = $post->getPostById($postId);
        
        $recieverId = $selectedPost->user_id;
        $time = $timeStamp->time;

        if($this->existNotification($recieverId,"like","post_id",$postId))
        {
            return;
        }

        if($recieverId == $senderId || empty($recieverId))
        {
            return;
        }

        $type = "like";
        $seen = "false";

        $newNotification = new Notification([
            'id' => NULL,
            'reciever_id' => $recieverId,
            'sender_id' => $senderId,
            'like_id' => $likeId,
            'comment_id' => NULL,
            'post_id' => $postId,
            'community_id' => NULL,
            'type' => $type,
            'seen' => $seen,
            'time' => $time
        ]);

        $this->registerLikePostNotification($newNotification);
    }

    public function commentNotification($senderId,$commentId,$postId,$time)
    {
        $post = new PostRepository();

        $selectedPost = $post->getPostById($postId);
        $recieverId = $selectedPost->user_id;

        if($recieverId == $senderId || empty($recieverId))
        {
            return;
        }

        $type = "comment";
        $seen = "false";

        $newNotification = new Notification([
            'id' => NULL,
            'reciever_id' => $recieverId,
            'sender_id' => $senderId,
            'like_id' => NULL,
            'comment_id' => $commentId,
            'post_id' => $postId,
            'community_id' => NULL,
            'type' => $type,
            'seen' => $seen,
            'time' => $time
        ]);

        $this->registerCommentNotification($newNotification);
    }

    public function postNotification($senderId,$postId,$communityId,$time)
    {
        $community = new CommunityRepository();
        
        $selectedCommunity = $community->getCommunity("id",$communityId);
        $recieverId = $selectedCommunity->user_id;
        
        
        if($recieverId == $senderId || empty($recieverId))
        {
            return;
        }

        $type = "post";
        $seen = "false";

        $newNotification = new Notification([
            'id' => NULL,
            'reciever_id' => $recieverId,
            'sender_id' => $senderId,
            'like_id' => NULL,
            'comment_id' => NULL,
            'post_id' => $postId,
            'community_id' => $communityId,
            'type' => $type,
            'seen' => $seen,
            'time' => $time
        ]);

        $this->registerPostNotification($newNotification);
    }

    public function markAllNottSeen($userId)
    {
        $seen = "true";
        $this->markAllSeen($userId,$seen);

        header("Location: view/notification.php");
        exit();
    }

    public function deleteUserNott($userId)
    {
        $this->deleteUsersNotifications($userId);

        header("Location: view/notification.php");
        exit();
    }
}