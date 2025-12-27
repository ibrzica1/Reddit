<?php

namespace Reddit\models;
use Reddit\models\Db;

class Comment extends Db
{
    public $id;
    public $text;
    public $user_id;
    public $post_id;
    public $comment_id;
    public $time;

    public function __construct($array)
    {
        $this->id = $array['id'];
        $this->text = $array['text'];
        $this->user_id = $array['user_id'];
        $this->post_id = $array['post_id'];
        $this->comment_id = $array['comment_id'];
        $this->time = $array['time'];
    }

    public static function commentLength($text)
    {
        return strlen($text) < 500;
    }
}