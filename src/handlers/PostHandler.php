<?php

namespace src\handlers;

use \src\models\Post;
use \src\models\PostLike;
use \src\models\PostComment;
use \src\models\User;
use \src\models\UserRelation;

class PostHandler
{
    public static function addPost($idUser, $type, $body)
    {
        $body = trim($body);

        if (!empty($idUser) && !empty($body)) {

            Post::insert([
                'id_user' => $idUser,
                'type' => $type,
                'body' => $body,
                'created_at' => date('Y-m-d H:i:s')
            ])->execute();
        }
    }

    public static function delete($idPost, $userId)
    {
        if (!empty($idPost) && !empty($userId)) {
            $post = Post::select()
                ->where('id', $idPost)
                ->where('id_user', $userId)
                ->get();

            if (count($post) > 0) {
                $post = $post[0];
                //deletar likes
                PostLike::delete()
                    ->where('id_post', $idPost)
                    ->execute();
                //deletar comentarios
                PostComment::delete()
                    ->where('id_post', $idPost)
                    ->execute();
                //caso seja foto deletar o arquivo da foto
                if ($post['type'] === 'photo') {
                    $photo = __DIR__.'/../..//public/media/uploads/' . $post['body'];
                    if(file_exists($photo)){
                        unlink($photo);
                    }                    
                }

                Post::delete()
                ->where('id', $idPost)
                ->execute();
            }
        }
    }

    public static function _postListToObject($postList, $loggedUserId)
    {
        $posts = [];

        foreach ($postList as $postItem) {
            $newPost = new Post();
            $newPost->id = $postItem['id'];
            $newPost->type = $postItem['type'];
            $newPost->body = $postItem['body'];
            $newPost->created_at = $postItem['created_at'];
            $newPost->mine  = false;

            if ($postItem['id_user'] == $loggedUserId) {
                $newPost->mine  = true;
            }

            //Pegar informações do usuário que postou

            $newUser = User::select()->where('id', $postItem['id_user'])->one();
            $newPost->user = new User();
            $newPost->user->id = $newUser['id'];
            $newPost->user->name = $newUser['name'];
            $newPost->user->avatar = $newUser['avatar'];

            //Informações Likes
            $likes = PostLike::select()
                ->where('id_post', $newPost->id)
                ->get();



            $newPost->likeCount = count($likes);
            $newPost->liked = self::isLiked($newPost->id, $loggedUserId);

            //Informações comments

            $newPost->comments = PostComment::select()->where('id_post', $newPost->id)->get();

            foreach ($newPost->comments as $key => $comment) {
                $newPost->comments[$key]['user'] = User::select()->where('id', $comment['id_user'])->one();
            }

            $posts[] = $newPost;
        }

        return $posts;
    }

    public static function isLiked($id_post, $userId)
    {
        $isLiked =  PostLike::select()
            ->where('id_post', $id_post)
            ->where('id_user', $userId)
            ->get();

        if (count($isLiked) > 0) {
            return true;
        }
        return false;
    }

    public static function deleteLike($id_post, $id_user)
    {
        PostLike::delete()
            ->where('id_post', $id_post)
            ->where('id_user', $id_user)
            ->execute();
    }
    public static function addLike($id_post, $id_user)
    {
        PostLike::insert([
            'id_post' => $id_post,
            'id_user' => $id_user,
            'created_at' => date('Y-m-d H:i:s')
        ])
            ->execute();
    }

    public static function addComment($id_post, $txt, $id_user)
    {
        PostComment::insert([
            'id_post' => $id_post,
            'id_user' => $id_user,
            'created_at' => date('Y-m-d H:i:s'),
            'body' => $txt
        ])->execute();
    }
    public static function getHomeFeed($id_user, $page)
    {
        $perPage = 2;
        // Pega relacionamento de quem o usuario segue
        $userList = UserRelation::select()->where('user_from', $id_user)->get();

        $users = [];

        foreach ($userList as $userItem) {
            $users[] = $userItem['user_to'];
        }

        $users[] = $id_user;

        //Monta os posts de quem é seguido pelo usuário        
        $postList = Post::select()
            ->where('id_user', 'in', $users)
            ->orderBy('created_at', 'desc')
            ->page($page, $perPage)
            ->get();

        $total = Post::select()
            ->where('id_user', 'in', $users)
            ->count();

        $pageCount = ceil($total / $perPage);


        $posts = self::_postListToObject($postList, $id_user);

        //
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page
        ];
    }

    public static function getUserFeed($id_user, $page, $loggedUserId)
    {
        $perPage = 2;
        $postList = Post::select()
            ->where('id_user', $id_user)
            ->orderBy('created_at', 'desc')
            ->page($page, $perPage)
            ->get();

        $total = Post::select()
            ->where('id_user', $id_user)
            ->count();

        $pageCount = ceil($total / $perPage);

        $posts = self::_postListToObject($postList,  $loggedUserId);

        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page
        ];
    }

    public static function getPhotosFrom($id_user)
    {
        $photosData = Post::select()
            ->where('id_user', $id_user)
            ->where('type', 'photo')
            ->get();

        $photos = [];

        foreach ($photosData as $photo) {
            $newPost = new Post;

            $newPost->id = $photo['id'];
            $newPost->type = $photo['type'];
            $newPost->body = $photo['body'];
            $newPost->created_at = $photo['created_at'];

            $photos[] = $newPost;
        }

        return $photos;
    }
}
