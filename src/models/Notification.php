<?php

namespace Reddit\models;
use Reddit\models\Db;

class Notification extends Db
{
    public $reciever_id;
    public $sender_id;
    public $like_id;
    public $comment_id;
    public $post_id;
    public $community_id;
    public $type;
    public $seen;
    public $time;

    public function registerLikeCommentNotification($recieverId,$senderId,$likeId,$commentId,$type,$seen,$time)
    {
        $stmt = $this->connection->prepare("INSERT INTO notification (reciever_id, sender_id, like_id, comment_id, type, seen, time)
        VALUES (:reciever_id, :sender_id, :like_id, :comment_id, :type, :seen, :time)");
        $stmt->bindParam(':reciever_id',$recieverId);
        $stmt->bindParam(':sender_id',$senderId);
        $stmt->bindParam(':like_id',$likeId);
        $stmt->bindParam(':comment_id',$commentId);
        $stmt->bindParam(':type',$type);
        $stmt->bindParam(':seen',$seen);
        $stmt->bindParam(':time',$time);

        $stmt->execute();
    }

    public function registerLikePostNotification($recieverId,$senderId,$likeId,$postId,$type,$seen,$time)
    {
        $stmt = $this->connection->prepare("INSERT INTO notification (reciever_id, sender_id, like_id, post_id, type, seen, time)
        VALUES (:reciever_id, :sender_id, :like_id, :post_id, :type, :seen, :time)");
        $stmt->bindParam(':reciever_id',$recieverId);
        $stmt->bindParam(':sender_id',$senderId);
        $stmt->bindParam(':like_id',$likeId);
        $stmt->bindParam(':post_id',$postId);
        $stmt->bindParam(':type',$type);
        $stmt->bindParam(':seen',$seen);
        $stmt->bindParam(':time',$time);

        $stmt->execute();
    }

    public function registerCommentNotification($recieverId,$senderId,$commentId,$postId,$type,$seen,$time)
    {
        $stmt = $this->connection->prepare("INSERT INTO notification (reciever_id, sender_id, comment_id, post_id, type, seen, time)
        VALUES (:reciever_id, :sender_id, :comment_id, :post_id, :type, :seen, :time)");
        $stmt->bindParam(':reciever_id',$recieverId);
        $stmt->bindParam(':sender_id',$senderId);
        $stmt->bindParam(':comment_id',$commentId);
        $stmt->bindParam(':post_id',$postId);
        $stmt->bindParam(':type',$type);
        $stmt->bindParam(':seen',$seen);
        $stmt->bindParam(':time',$time);

        $stmt->execute();
    }

    public function registerPostNotification($recieverId,$senderId,$postId,$communityId,$type,$seen,$time)
    {
        $stmt = $this->connection->prepare("INSERT INTO notification (reciever_id, sender_id, post_id, community_id, type, seen, time)
        VALUES (:reciever_id, :sender_id, :post_id, :community_id, :type, :seen, :time)");
        $stmt->bindParam(':reciever_id',$recieverId);
        $stmt->bindParam(':sender_id',$senderId);
        $stmt->bindParam(':post_id',$postId);
        $stmt->bindParam(':community_id',$communityId);
        $stmt->bindParam(':type',$type);
        $stmt->bindParam(':seen',$seen);
        $stmt->bindParam(':time',$time);

        $stmt->execute();
    }
}