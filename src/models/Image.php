<?php

namespace Reddit\models;
use Reddit\models\Db;

class Image extends Db
{
    const ALLOWED_EXTENSIONS = ["jpg", "jpeg", "png", "gif"];
    const MAX_FILE_SIZE = 40 * 1024 * 1024;
    const MAX_IMAGE_WIDTH = 2920;
    const MAX_IMAGE_HEIGHT = 2924;

    public function getCommunityImage(int $id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM image WHERE community_id = :id");
        $stmt->bindParam(':id',$id);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function getPostImage(int $id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM image WHERE post_id = :id");
        $stmt->bindParam(':id',$id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function uploadImage(string $tmpName, string $name, int $postId, int $userId): void
    {
        $finalPath = __DIR__ . "/../../images/uploaded/$name";

        move_uploaded_file($tmpName, $finalPath);
        $stmt = $this->connection->prepare("INSERT INTO image (name,post_id,user_id) 
        VALUES (:name,:post_id,:user_id)");
        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':post_id',$postId);
        $stmt->bindParam(':user_id',$userId);
        $stmt->execute();
        
    }

    public function uploadCommunityImage(string $tmpName, string $name, int $communityId, int $userId): void
    {
        $finalPath = __DIR__ . "/../../images/community/$name";

        move_uploaded_file($tmpName, $finalPath);
        $stmt = $this->connection->prepare("INSERT INTO image (name,community_id,user_id) 
        VALUES (:name,:community_id,:user_id)");
        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':community_id',$communityId);
        $stmt->bindParam(':user_id',$userId);
        $stmt->execute();
        
    }

    public function generateRandomName(string $extension): string
    {
        return uniqid().".".$extension;
    }

    public function isValidSize(int $image): bool
    {
        $size = $image;

        return $size < self::MAX_FILE_SIZE;
    }

    public function isValidDimension(string $image): bool
    {
        $image_info = getimagesize($image);

        $imageWidth = $image_info[0];
        $imageHeight = $image_info[1];

        return $imageWidth < self::MAX_IMAGE_WIDTH && $imageHeight < self::MAX_IMAGE_HEIGHT;
        
    }

    public function isValidExtension(string $image): bool
    {
        $imageType = pathinfo($image, PATHINFO_EXTENSION);

        return in_array($imageType, self::ALLOWED_EXTENSIONS);
    }
    
    public function deleteImage(string $attribute, mixed $value): void
    {
        $stmt = $this->connection->prepare("DELETE FROM image WHERE $attribute = :value");
        $stmt->bindParam(':value',$value);

        $stmt->execute();
    }
}