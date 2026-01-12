<?php

namespace Reddit\models;

use BcMath\Number;
use InvalidArgumentException;

class Post
{
    private ?int $id;
    private string $title;
    private ?string $text;
    private int $user_id;
    private int $community_id;
    private string $time;

    public function __construct($array)
    {
        $this->id = $array['id'] ?? NULL;
        $this->title = $array['title'];
        $this->text = $array['text'] ?? NULL;
        $this->user_id = $array['user_id'];
        $this->community_id = $array['community_id'];
        $this->time = $array['time'];
    }

    public function setTitle(string $title): void
    {
        if(!self::titleLength($title)){
            throw new InvalidArgumentException("Title length is too long");
        }
        $this->title = $title;
    }

    public function setText(string $text): void
    {
        if(!self::textLength($text)){
            throw new InvalidArgumentException("Text length is too long");
        }
        $this->text = $text;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getUser_id(): int
    {
        return $this->user_id;
    }

    public function getCommunity_id(): int
    {
        return $this->community_id;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public static function titleLength($title)
    {
        return strlen($title) >= 2 && strlen($title) <= 300;
    }

    public static function textLength($text)
    {
        return strlen($text) >= 2 && strlen($text) <= 1000;
    }

}