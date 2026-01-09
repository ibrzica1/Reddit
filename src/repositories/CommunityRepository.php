<?php

namespace Reddit\repositories;
use Reddit\models\Db;
use Reddit\models\Community;

class CommunityRepository extends Db
{
    public function getCommunity(string $attribute, mixed $value): Community
    {
        $stmt = $this->connection->prepare("SELECT * FROM community WHERE $attribute = :value");
        $stmt->bindParam(':value',$value);
        $stmt->execute();
        $result = $stmt->fetch();
        $community = new Community($result);
        return $community;
    }

    public function getCommunities(string $attribute, mixed $value): Array
    {
        $stmt = $this->connection->prepare("SELECT * FROM community WHERE $attribute = :value");
        $stmt->bindParam(':value',$value);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $communities = [];
        foreach($result as $item)
        {
            $community = new Community($item);
            array_push($communities,$community);
        }
        
        return $communities;
    }

    public function registerCommunity(Community $community)
    {
        $stmt = $this->connection->prepare("INSERT INTO community (name, description, user_id, time)
        VALUES (:name, :description, :user_id, :time)");
        $stmt->bindValue(':name',$community->getName(), \PDO::PARAM_STR);
        $stmt->bindValue(':description',$community->getDescription(), \PDO::PARAM_STR);
        $stmt->bindValue(':user_id',$community->getUser_id(), \PDO::PARAM_INT);
        $stmt->bindValue(':time',$community->getTime(), \PDO::PARAM_STR);

        $stmt->execute();
    }

    public function deleteCommunity(int $communityId): void
    {
        $stmt = $this->connection->prepare("DELETE FROM community WHERE id = :id");
        $stmt->bindParam(':id',$communityId);

        $stmt->execute();
    }

    public function searchCommunity($attribute, $value)
    {
        $stmt = $this->connection->prepare("SELECT * FROM community WHERE $attribute LIKE :value");
        $search = "%". $value . "%";
        $stmt->bindParam(':value',$search);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}