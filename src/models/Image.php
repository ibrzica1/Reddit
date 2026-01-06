<?php

namespace Reddit\models;
use Reddit\models\Db;

class Image extends Db
{
    private ?int $id;
    private string $name;
    private ?int $post_id;
    private ?int $community_id;
    private int $user_id;

    const ALLOWED_EXTENSIONS = ["jpg", "jpeg", "png", "gif"];
    const MAX_FILE_SIZE = 40 * 1024 * 1024;
    const MAX_IMAGE_WIDTH = 2920;
    const MAX_IMAGE_HEIGHT = 2924;

    public function __construct($array)
    {
        $this->id = $array['id'] ?? NULL;
        $this->name = $array['name'];
        $this->post_id = $array['post_id'] ?? NULL;
        $this->community_id = $array['community_id'] ?? NULL;
        $this->user_id = $array['user_id'];
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPost_id(): ?int
    {
        return $this->post_id;
    }

    public function getCommunity_id(): ?int
    {
        return $this->community_id;
    }

    public function getUser_id(): int
    {
        return $this->user_id;
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