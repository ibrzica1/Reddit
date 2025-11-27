<?php

namespace Reddit\models;
use Reddit\models\Db;

class Like extends Db
{
    protected $userId;
    public $postId;
    public $commentId;

    public function __construct($idUser)
    {
        $this->userId = $idUser;
    }
}