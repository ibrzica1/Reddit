<?php

namespace Reddit\models;
use Reddit\models\Db;

class Search extends Db 
{
    public function SearchAll($search)
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