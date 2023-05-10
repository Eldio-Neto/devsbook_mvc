<?php
namespace src\models;
use \core\Model;

class Post extends Model {
    public $id;
    public $id_user;
    public $type;
    public $created_at;
    public $body;
    public $user;
    public $mine;
    public $likeCount;
    public $comments;
    public $liked;
}