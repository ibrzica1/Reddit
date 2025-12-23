<?php

namespace Reddit\models;
use Reddit\models\Db;

class Search extends Db 
{

    public function searchPost($search,$commId)
    {
        $stmt = $this->connection->prepare("SELECT * FROM post 
        WHERE (title LIKE :search OR text LIKE :search) AND community_id = :comm_id");
        $searchTerm = "%$search%";
        $stmt->bindValue(':search',$searchTerm);
        $stmt->bindValue(':comm_id',$commId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function searchProfile($search,$userId)
    {
        $query = "
        SELECT
            'community' AS type,
            id,
            name AS display_name,
            description AS info,
            NULL AS picture
        FROM community
        WHERE name LIKE :search1
        AND user_id = :user_id1
        UNION ALL
        SELECT
            'post' AS type,
            id,
            title AS display_name,
            SUBSTRING(text,1,50) AS info,
            community_id AS picture 
        FROM post 
        WHERE (title LIKE :search2 OR text LIKE :search2) 
        AND user_id = :user_id2
        LIMIT 10";
        
        $stmt = $this->connection->prepare($query);
        $searchTerm = "%$search%";
        $stmt->bindValue(':search1',$searchTerm);
        $stmt->bindValue(':search2',$searchTerm);
        $stmt->bindValue(':user_id1',$userId);
        $stmt->bindValue(':user_id2',$userId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function searchAll($search)
    {
        $query = "
        SELECT
            'community' AS type,
            id,
            name AS display_name,
            description AS info,
            NULL AS picture
        FROM community
        WHERE name LIKE :search1
        UNION ALL
        SELECT
            'post' AS type,
            id,
            title AS display_name,
            SUBSTRING(text,1,50) AS info,
            community_id AS picture 
        FROM post 
        WHERE title LIKE :search2 OR text LIKE :search2
        UNION ALL
        SELECT
            'user' AS type,
            id,
            username AS display_name,
            NULL AS info,
            avatar AS picture
        FROM user
        WHERE username LIKE :search3 
        LIMIT 10";
        
        $stmt = $this->connection->prepare($query);
        $searchTerm = "%$search%";
        $stmt->bindValue(':search1',$searchTerm);
        $stmt->bindValue(':search2',$searchTerm);
        $stmt->bindValue(':search3',$searchTerm);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}