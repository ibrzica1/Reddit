<?php

namespace Reddit\models;
use Reddit\models\Db;

class Image extends Db
{
    public $id;
    public $name;
    public $post_id;
    public $community_id;
    public $user_id;

    const ALLOWED_EXTENSIONS = ["jpg", "jpeg", "png", "gif"];
    const MAX_FILE_SIZE = 40 * 1024 * 1024;
    const MAX_IMAGE_WIDTH = 2920;
    const MAX_IMAGE_HEIGHT = 2924;

    public function __construct($array)
    {
        $this->id = $array['id'];
        $this->name = $array['name'];
        $this->post_id = $array['post_id'];
        $this->community_id = $array['community_id'];
        $this->user_id = $array['user_id'];
    }

    public static function generateRandomName(string $extension): string
    {
        return uniqid().".".$extension;
    }

    public static function isValidSize(int $image): bool
    {
        $size = $image;

        return $size < self::MAX_FILE_SIZE;
    }

    public static function isValidDimension(string $image): bool
    {
        $image_info = getimagesize($image);

        $imageWidth = $image_info[0];
        $imageHeight = $image_info[1];

        return $imageWidth < self::MAX_IMAGE_WIDTH && $imageHeight < self::MAX_IMAGE_HEIGHT;
        
    }

    public static function isValidExtension(string $image): bool
    {
        $imageType = pathinfo($image, PATHINFO_EXTENSION);

        return in_array($imageType, self::ALLOWED_EXTENSIONS);
    }
}