<?php

namespace Reddit\repositories;

use Reddit\models\Db;
use Reddit\models\Notification;

class NotificationRepository extends Db
{
    
    public function getUserNotifications(int $recieverId): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM notification 
        WHERE reciever_id = :reciever_id 
        ORDER BY time DESC");
        $stmt->bindParam(':reciever_id',$recieverId);
        $stmt->execute();

        $results = $stmt->fetchAll();
        $notifications = [];

        foreach($results as $result)
        {
            $notification = new Notification($result);
            array_push($notifications,$notification);
        }
        return $notifications;
    }

    public function registerLikeCommentNotification(Notification $notification): void
    {
        $stmt = $this->connection->prepare("INSERT INTO notification (reciever_id, sender_id, like_id, comment_id, type, seen, time)
        VALUES (:reciever_id, :sender_id, :like_id, :comment_id, :type, :seen, :time)");
        $stmt->bindParam(':reciever_id',$notification->getReciever_id());
        $stmt->bindParam(':sender_id',$notification->getSender_id());
        $stmt->bindParam(':like_id',$notification->getLike_id());
        $stmt->bindParam(':comment_id',$notification->getComment_id());
        $stmt->bindParam(':type',$notification->getType());
        $stmt->bindParam(':seen',$notification->getSeen());
        $stmt->bindParam(':time',$notification->getTime());

        $stmt->execute();
    }

    public function registerLikePostNotification(Notification $notification): void
    {
        $stmt = $this->connection->prepare("INSERT INTO notification (reciever_id, sender_id, like_id, post_id, type, seen, time)
        VALUES (:reciever_id, :sender_id, :like_id, :post_id, :type, :seen, :time)");
        $stmt->bindParam(':reciever_id',$notification->getReciever_id());
        $stmt->bindParam(':sender_id',$notification->getSender_id());
        $stmt->bindParam(':like_id',$notification->getLike_id());
        $stmt->bindParam(':post_id',$notification->getPost_id());
        $stmt->bindParam(':type',$notification->getType());
        $stmt->bindParam(':seen',$notification->getSeen());
        $stmt->bindParam(':time',$notification->getTime());

        $stmt->execute();
    }

    public function registerCommentNotification(Notification $notification): void
    {
        $stmt = $this->connection->prepare("INSERT INTO notification (reciever_id, sender_id, comment_id, post_id, type, seen, time)
        VALUES (:reciever_id, :sender_id, :comment_id, :post_id, :type, :seen, :time)");
        $stmt->bindParam(':reciever_id',$notification->getReciever_id());
        $stmt->bindParam(':sender_id',$notification->getSender_id());
        $stmt->bindParam(':comment_id',$notification->getComment_id());
        $stmt->bindParam(':post_id',$notification->getPost_id());
        $stmt->bindParam(':type',$notification->getType());
        $stmt->bindParam(':seen',$notification->getSeen());
        $stmt->bindParam(':time',$notification->getTime());

        $stmt->execute();
    }

    public function registerPostNotification(Notification $notification): void
    {
        $stmt = $this->connection->prepare("INSERT INTO notification (reciever_id, sender_id, post_id, community_id, type, seen, time)
        VALUES (:reciever_id, :sender_id, :post_id, :community_id, :type, :seen, :time)");
        $stmt->bindParam(':reciever_id',$notification->getReciever_id());
        $stmt->bindParam(':sender_id',$notification->getSender_id());
        $stmt->bindParam(':post_id',$notification->getPost_id());
        $stmt->bindParam(':community_id',$notification->getCommunity_id());
        $stmt->bindParam(':type',$notification->getType());
        $stmt->bindParam(':seen',$notification->getSeen());
        $stmt->bindParam(':time',$notification->getTime());

        $stmt->execute();
    }

    public function unreadNotifications(int $recieverId): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM notification WHERE 
        reciever_id = :reciever_id 
        AND seen = 'false'
        ORDER BY time DESC");
        $stmt->bindParam(':reciever_id',$recieverId);
        $stmt->execute();

        $results = $stmt->fetchAll();
        $notifications = [];

        foreach($results as $result)
        {
            $notification = new Notification($result);
            array_push($notifications,$notification);
        }
        return $notifications;
    }

    public function markAllSeen(int $recieverId,string $seen): void
    {
        $stmt = $this->connection->prepare("UPDATE notification
        SET seen = :seen 
        WHERE reciever_id = :reciever_id");
        $stmt->bindParam(':seen',$seen);
        $stmt->bindParam(':reciever_id',$recieverId);
        $stmt->execute();
    }

    public function changeSeenStatus(int $notificationId,string $seen): void
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

    public function existNotification(int $recieverId,string $type,string $attribute,mixed $value): int
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

    public function deleteNotifications(string $attribute, mixed $value): void
    {
        $stmt = $this->connection->prepare("DELETE FROM notification WHERE $attribute = :value");
        $stmt->bindParam(':value',$value);

        $stmt->execute();
    }
}