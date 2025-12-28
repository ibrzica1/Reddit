<?php

namespace Reddit\models;
use Reddit\models\Db;

class Like extends Db
{
    public $id;
    public $user_id;
    public $post_id;
    public $comment_id;
    public $status;

    public function __construct($array)
    {
        $this->id = $array['id'];
        $this->user_id = $array['user_id'];
        $this->post_id = $array['post_id'];
        $this->comment_id = $array['comment_id'];
        $this->status = $array['status'];
    }
   
}