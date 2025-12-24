<?php

namespace Reddit\controllers;

use Reddit\models\Like;
use Reddit\services\KarmaService;

class LikeController extends Like
{
    public function addCommentDislikeController($userId,$commId)
    {
        $likeItem = $this->getLike("comment_id",$commId,$userId);
        $karmaService = new KarmaService();

        if(empty($likeItem))
        {
            $status = "disliked";
            $this->addLikeComment($commId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }
        
        if($likeItem['status'] == "disliked")
        {
            $likeId = $likeItem['id'];
            $this->deleteLike($likeId);
            $karmaService->updateUserKarma($userId);
            $status = "neutral";
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem['status'] == "liked")
        {
            $status = "disliked";
            $this->updateLike("comment_id",$commId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }   
    }

    public function addCommentLikeController($userId,$commId)
    {
        $notificationController = new NotificationController();
        $karmaService = new KarmaService();
        $likeItem = $this->getLike("comment_id",$commId,$userId);

        if(empty($likeItem))
        {
            $status = "liked";
            $this->addLikeComment($commId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $likeId = $this->connection->lastInsertId();
            $notificationController->likeCommentNotification($userId,$likeId,$commId);
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem['status'] == "liked")
        {
            $likeId = $likeItem['id'];
            $this->deleteLike($likeId);
            $karmaService->updateUserKarma($userId);
            $status = "neutral";
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem['status'] == "disliked")
        {
            $status = "liked";
            $this->updateLike("comment_id",$commId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }     
    }

    public function addPostDislikeController($userId,$postId)
    {
        $karmaService = new KarmaService();
        $likeItem = $this->getLike("post_id",$postId,$userId);

        if(empty($likeItem))
        {
            $status = "disliked";
            $this->addLikePost($postId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }
        
        if($likeItem['status'] == "disliked")
        {
            $likeId = $likeItem['id'];
            $this->deleteLike($likeId);
            $karmaService->updateUserKarma($userId);
            $status = "neutral";
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem['status'] == "liked")
        {
            $status = "disliked";
            $this->updateLike("post_id",$postId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }   
    }

    
    public function addPostLikeController($userId,$postId)
    {
        $notificationController = new NotificationController();
        $karmaService = new KarmaService();
        $likeItem = $this->getLike("post_id",$postId,$userId);

        if(empty($likeItem))
        {
            $status = "liked";
            $this->addLikePost($postId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $likeId = $this->connection->lastInsertId();
            $notificationController->likePostNotification($userId,$likeId,$postId);
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem['status'] == "liked")
        {
            $likeId = $likeItem['id'];
            $this->deleteLike($likeId);
            $karmaService->updateUserKarma($userId);
            $status = "neutral";
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem['status'] == "disliked")
        {
            $status = "liked";
            $this->updateLike("post_id",$postId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }     
    }
}
