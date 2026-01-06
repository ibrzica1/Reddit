<?php

namespace Reddit\controllers;

use Reddit\models\Like;
use Reddit\services\KarmaService;
use Reddit\repositories\LikeRepository;

class LikeController extends LikeRepository
{
    public function addCommentDislikeController($userId,$commId)
    {
        $likeItem = $this->getLike("comment_id",$commId,$userId);
        $karmaService = new KarmaService();

        if($likeItem === null)
        {
            $status = "disliked";
            $newLike = new Like([
                'id' => null,
                'user_id' => $userId,
                'post_id' => NULL,
                'comment_id' => $commId,
                'status' => $status
            ]);
            $this->addLikeComment($newLike);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }
        
        if($likeItem !== null && $likeItem->getStatus() === "disliked")
        {
            $likeId = $likeItem->getId();
            $this->deleteLike($likeId);
            $karmaService->updateUserKarma($userId);
            $status = "neutral";
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem !== null && $likeItem->getStatus() === "liked")
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

        if($likeItem === null)
        {
            $status = "liked";
            $newLike = new Like([
                'id' => null,
                'user_id' => $userId,
                'post_id' => NULL,
                'comment_id' => $commId,
                'status' => $status
            ]);
            $this->addLikeComment($newLike);
            $karmaService->updateUserKarma($userId);
            $likeId = $this->connection->lastInsertId();
            $notificationController->likeCommentNotification($userId,$likeId,$commId);
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem !== null && $likeItem->getStatus() === "liked")
        {
            $likeId = $likeItem->getId();
            $this->deleteLike($likeId);
            $karmaService->updateUserKarma($userId);
            $status = "neutral";
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem !== null && $likeItem->getStatus() === "disliked")
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

        if($likeItem === null)
        {
            $status = "disliked";
            $newLike = new Like([
                'id' => null,
                'user_id' => $userId,
                'post_id' => $postId,
                'comment_id' => NULL,
                'status' => $status
            ]);
            $this->addLikePost($newLike);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }
        
        if($likeItem !== null && $likeItem->getStatus() === "disliked")
        {
            $likeId = $likeItem->getId();
            $this->deleteLike($likeId);
            $karmaService->updateUserKarma($userId);
            $status = "neutral";
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem !== null && $likeItem->getStatus() === "liked")
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

        if($likeItem === false ||$likeItem === null)
        {
            $status = "liked";
            $newLike = new Like([
                'id' => null,
                'user_id' => $userId,
                'post_id' => $postId,
                'comment_id' => NULL,
                'status' => $status
            ]);
            $this->addLikePost($newLike);
            $karmaService->updateUserKarma($userId);
            $likeId = $this->connection->lastInsertId();
            $notificationController->likePostNotification($userId,$likeId,$postId);
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem !== false && $likeItem->getStatus() === "liked")
        {
            $likeId = $likeItem->getId();
            $this->deleteLike($likeId);
            $karmaService->updateUserKarma($userId);
            $status = "neutral";
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem !== false && $likeItem->getStatus() === "disliked")
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
