<?php
namespace src\models;
use \core\Model;

class PostLike extends Model {
    public $id;
    public $id_user;
    public $id_post;
    public $created_at;
}