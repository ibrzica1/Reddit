<?php

namespace Reddit\models;

class Notification 
{
    public $id;
    public $reciever_id;
    public $sender_id;
    public $like_id;
    public $comment_id;
    public $post_id;
    public $community_id;
    public $type;
    public $seen;
    public $time;

    public function __construct($array)
    {
        $this->id = $array['id'];
        $this->reciever_id = $array['reciever_id'];
        $this->sender_id = $array['sender_id'];
        $this->like_id = $array['like_id'];
        $this->comment_id = $array['comment_id'];
        $this->post_id = $array['post_id'];
        $this->community_id = $array['community_id'];
        $this->type = $array['type'];
        $this->seen = $array['seen'];
        $this->time = $array['time'];
    }
}