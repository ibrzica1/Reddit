<?php

namespace Reddit\models;

class Post
{
    public $id;
    public $title;
    public $text;
    public $user_id;
    public $community_id;
    public $time;

    public function __construct($array)
    {
        $this->id = $array['id'];
        $this->title = $array['title'];
        $this->text = $array['text'];
        $this->user_id = $array['user_id'];
        $this->community_id = $array['community_id'];
        $this->time = $array['time'];
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