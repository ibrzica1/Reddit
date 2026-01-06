<?php

namespace Reddit\models;

use InvalidArgumentException;
use Reddit\models\Db;

class Like extends Db
{
    private ?int $id;
    private int $user_id;
    private ?int $post_id;
    private ?int $comment_id;
    private string $status;

    public function __construct($array)
    {
        $this->id = $array['id'] ?? NULL;
        $this->user_id = $array['user_id'];
        $this->post_id = $array['post_id'] ?? NULL;
        $this->comment_id = $array['comment_id'] ?? NULL;
        $this->status = $array['status'];
    }

    public function setStatus($status): void
    {
        $statuses = ["disliked","liked","neutral"];

        if(!in_array($status,$statuses)){
            throw new InvalidArgumentException("Invalid status");
        }
        $this->status = $status;
    }
   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser_id(): int
    {
        return $this->user_id;
    }

    public function getPost_id(): ?int
    {
        return $this->post_id;
    }

    public function getComment_id(): ?int
    {
        return $this->comment_id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}