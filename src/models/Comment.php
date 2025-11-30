<?php

namespace Reddit\models;
use Reddit\models\Db;

class Comment extends Db
{
    public $text;
    public $user_id;
    public $post_id;
    public $time;

    
}