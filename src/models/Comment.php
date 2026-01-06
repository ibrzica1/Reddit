<?php

namespace Reddit\models;

use InvalidArgumentException;
use Reddit\models\Db;

class Comment extends Db
{
    private ?int $id;
    private string $text;
    private int $user_id;
    private int $post_id;
    private ?int $comment_id;
    private string $time;

    public function __construct($array)
    {
        $this->id = $array['id'] ?? NULL;
        $this->text = $array['text'];
        $this->user_id = $array['user_id'];
        $this->post_id = $array['post_id'];
        $this->comment_id = $array['comment_id'] ?? NULL;
        $this->time = $array['time'];
    }

    public function setText(string $text): void
    {
        if(!self::commentLength($text)){
            throw new InvalidArgumentException("Text length is too long");
        }
        $this->text = $text;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getUser_id(): int
    {
        return $this->user_id;
    }

    public function getPost_id(): int
    {
        return $this->post_id;
    }

    public function getComment_id(): ?int
    {
        return $this->comment_id;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public static function commentLength($text)
    {
        return strlen($text) < 500;
    }
}