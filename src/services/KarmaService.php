<?php

namespace Reddit\services;

require_once "vendor/autoload.php";

use Reddit\models\Db;

class KarmaService extends Db
{
    public function updateUserKarma($userId)
    {
        $query = "
           SELECT COALESCE(
                SUM(
                    CASE
                        WHEN status = 'liked' THEN 1
                        WHEN status = 'disliked' THEN -1
                        ELSE 0
                    END
                ), 0
            ) AS karma
            FROM likes
            WHERE user_id = :user_id
        ";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':user_id',$userId);
        $stmt->execute();

        $newKarma = (int) $stmt->fetchColumn();
        if($newKarma < 0) $newKarma = 0;

        $stmt = $this->connection->prepare("UPDATE user
        SET karma = :karma
        WHERE  id = :id
        ");
        $stmt->bindParam(':karma',$newKarma);
        $stmt->bindParam(':id',$userId);
        $stmt->execute();
    }
}