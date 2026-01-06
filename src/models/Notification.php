<?php

namespace Reddit\models;

class Notification 
{
    private ?int $id;
    private int $reciever_id;
    private int $sender_id;
    private ?int $like_id;
    private ?int $comment_id;
    private ?int $post_id;
    private ?int $community_id;
    private string $type;
    private string $seen;
    private string $time;

    public function __construct($array)
    {
        $this->id = $array['id'] ?? NULL;
        $this->reciever_id = $array['reciever_id'];
        $this->sender_id = $array['sender_id'];
        $this->like_id = $array['like_id'] ?? NULL;
        $this->comment_id = $array['comment_id'] ?? NULL;
        $this->post_id = $array['post_id'] ?? NULL;
        $this->community_id = $array['community_id'] ?? NULL;
        $this->type = $array['type'];
        $this->seen = $array['seen'];
        $this->time = $array['time'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReciever_id(): int
    {
        return $this->reciever_id;
    }

    public function getSender_id(): int
    {
        return $this->sender_id;
    }

    public function getLike_id(): ?int
    {
        return $this->like_id;
    }

    public function getComment_id(): ?int
    {
        return $this->comment_id;
    }

    public function getPost_id(): ?int
    {
        return $this->post_id;
    }

    public function getCommunity_id(): ?int
    {
        return $this->community_id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSeen(): string
    {
        return $this->seen;
    }

    public function getTime(): string
    {
        return $this->time;
    }
}