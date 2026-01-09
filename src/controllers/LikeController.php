<?php

namespace Reddit\controllers;

use Reddit\services\SessionService;
use Reddit\models\Like;
use Reddit\services\KarmaService;
use Reddit\repositories\LikeRepository;
use Reddit\controllers\NotificationController;

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

        if($likeItem->getStatus() === "neutral")
        {
            $status = "disliked";
            $this->updateLike("comment_id",$commId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }
        
        if($likeItem->getStatus() === "disliked")
        {
            $status = "neutral";
            $this->updateLike("comment_id",$commId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem->getStatus() === "liked")
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

        if($likeItem->getStatus() === "neutral")
        {
            $status = "liked";
            $this->updateLike("comment_id",$commId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem->getStatus() === "liked")
        {
            $status = "neutral";
            $this->updateLike("comment_id",$commId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("comment_id",$commId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem->getStatus() === "disliked")
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

        if($likeItem->getStatus() === "neutral")
        {
            $status = "disliked";
            $this->updateLike("post_id",$postId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }
        
        if($likeItem->getStatus() === "disliked")
        {
            $status = "neutral";
            $this->updateLike("post_id",$postId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem->getStatus() === "liked")
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

        if($likeItem === null || empty($likeItem))
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
            error_log('LIKE ID: ' . $likeId);
            $notificationController->likePostNotification($userId,$likeId,$postId);
            $newCount = $this->getLikeCount("post_id",$postId);
             error_log('COUNT: ' . $newCount);
            $data = [$newCount,$status];
            error_log('DATA: ' . $data[1]);
            return $data;
        }

        if($likeItem->getStatus() === "neutral")
        {
            $status = "liked";
            $this->updateLike("post_id",$postId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem->getStatus() === "liked")
        {
            $status = "neutral";
            $this->updateLike("post_id",$postId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem->getStatus() === "disliked")
        {
            $status = "liked";
            $this->updateLike("post_id",$postId,$status,$userId);
            $karmaService->updateUserKarma($userId);
            $newCount = $this->getLikeCount("post_id",$postId);
            $data = [$newCount,$status];
            return $data;
        }     
    }

    public function deleteLikeController($likeId)
    {
        $session = new SessionService();
        $notificationController = new NotificationController();

        if(!isset($likeId))
        {
        $message = "You didnt send like Id";
        $session->setSession("message",$message);
        header("Location: view/index.php");
        exit();
        }

        $this->deleteLike($likeId);
        $notificationController->deleteNotifications("like_id",$likeId);
    }
}
