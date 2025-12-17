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

    public function getUserNotifications($recieverId)
    {
        $stmt = $this->connection->prepare("SELECT * FROM notification 
        WHERE reciever_id = :reciever_id 
        ORDER BY time DESC");
        $stmt->bindParam(':reciever_id',$recieverId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

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

    public function unreadNotifications($recieverId)
    {
        $stmt = $this->connection->prepare("SELECT * FROM notification WHERE 
        reciever_id = :reciever_id 
        AND seen = 'false'
        ORDER BY time DESC");
        $stmt->bindParam(':reciever_id',$recieverId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function markAllSeen($recieverId,$seen)
    {
        $stmt = $this->connection->prepare("UPDATE notification
        SET seen = :seen 
        WHERE reciever_id = :reciever_id");
        $stmt->bindParam(':seen',$seen);
        $stmt->bindParam(':reciever_id',$recieverId);
        $stmt->execute();
    }

    public function changeSeenStatus($notificationId,$seen)
    {
        $acceptedTypes = ["true","false"];

        if(!in_array($seen,$acceptedTypes))
        {
            return;
        }

        $stmt = $this->connection->prepare("UPDATE notification
        SET seen = :seen 
        WHERE id = :id");
        $stmt->bindParam(':seen',$seen);
        $stmt->bindParam(':id',$notificationId);
        $stmt->execute();
    }

    public function existNotification($recieverId,$type,$attribute,$value)
    {
        $stmt = $this->connection->prepare("SELECT 1 FROM notification WHERE 
        reciever_id = :reciever_id 
        AND type = :type
        AND $attribute = :value");
        $stmt->bindParam(':reciever_id',$recieverId);
        $stmt->bindParam(':type',$type);
        $stmt->bindParam(':value',$value);
        $stmt->execute();
        $stmt->fetch();
        $number = $stmt->rowCount();
        
        if($number > 0){
            return true;
        }
        else{
            return false;
        }
    }
}