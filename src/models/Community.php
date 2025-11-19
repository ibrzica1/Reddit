<?php

namespace Reddit\models;
use Reddit\models\Db;

class Community extends Db
{
    public $name;
    public $description;
    public $user_id;
    public $time;

    public function getCommunity(string $attribute, mixed $value): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM community WHERE $attribute = :value");
        $stmt->bindParam(':value',$value);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function nameLength($name)
    {
        return strlen($name) >= 3 && strlen($name) <= 21;
    }

    public function descriptionLength($description)
    {
        return strlen($description) >= 3 && strlen($description) <= 500;
    }

    public function registerCommunity($name, $description, $user_id, $time)
    {
        $stmt = $this->connection->prepare("INSERT INTO community (name, description, user_id, time)
        VALUES (:name, :description, :user_id, :time)");
        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':description',$description);
        $stmt->bindParam(':user_id',$user_id);
        $stmt->bindParam(':time',$time);

        $stmt->execute();
    }

    public function deleteCommunity(int $communityId): void
    {
        $stmt = $this->connection->prepare("DELETE FROM community WHERE id = :id");
        $stmt->bindParam(':id',$communityId);

        $stmt->execute();
    }
}