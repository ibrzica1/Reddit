<?php

namespace Reddit\models;

use InvalidArgumentException;

class Community 
{
    private ?int $id;
    private string $name;
    private string $description;
    private int $user_id;
    private string $time;

    public function __construct($array)
    {
        $this->id = $array['id'] ?? NULL;
        $this->name = $array['name'];
        $this->description = $array['description'];
        $this->user_id = $array['user_id'];
        $this->time = $array['time'];
    }

    public function setName(string $name): void
    {
        if(!self::nameLength($name)){
            throw new InvalidArgumentException("Name is too long");
        }
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        if(!self::descriptionLength($description)){
            throw new InvalidArgumentException("Description is too long");
        }
        $this->description = $description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUser_id(): int
    {
        return $this->user_id;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public static function nameLength($name)
    {
        return strlen($name) >= 3 && strlen($name) <= 21;
    }

    public static function descriptionLength($description)
    {
        return strlen($description) >= 3 && strlen($description) <= 500;
    }
    
}