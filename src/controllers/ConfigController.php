<?php

namespace src\controllers;

use \core\Controller;

use \src\handlers\UserHandler;

class ConfigController extends Controller
{

    private $loggedUser;

    public function __construct()
    {
        $this->loggedUser = UserHandler::checkLogin();
        if ($this->loggedUser === false) {
            $this->redirect('/login');
        }
    }
    public function index()
    {
        $user = UserHandler::getUser($this->loggedUser->id);

        $flash = '';

        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $this->render('config', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'flash' => $flash
        ]);
    }

    public function updateUserInfo()
    {
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $birthdate = filter_input(INPUT_POST, 'birthdate');
        $city = filter_input(INPUT_POST, 'city');
        $work = filter_input(INPUT_POST, 'work');
        $password = filter_input(INPUT_POST, 'password');
        $passwordConfirmation = filter_input(INPUT_POST, 'password_confirmation');

        if ($name && $email) {
            $updateFields = [];

            $user = UserHandler::getUser($this->loggedUser->id);

            //Validando e Alterando email do Usuário
            if ($user->email != $email) {
                if (UserHandler::emailExists($email) == false) {
                    $updateFields['email'] = $email;
                } else {
                    $_SESSION['flash'] = 'Email já cadastrado!';
                    $this->redirect('/config');
                }
            }

            // Validando e Alterando a data de nascimento do usuário

            $birthdate = explode('/', $birthdate);

            if (count($birthdate) != 3) {
                $_SESSION['flash'] = 'Data preenchida não confere. ex: 00/00/0000';
                $this->redirect('/config');
            }

            $birthdate = $birthdate[2] . '-' . $birthdate[1] . '-' . $birthdate[0];

            if (strtotime($birthdate) === false) {
                $_SESSION['flash'] = 'Data preenchida não confere. ex: 00/00/0000';
                $this->redirect('/config');
            }

            $dateFrom = new \DateTime($birthdate);
            $limit = new \DateTime('today');

            if ($limit < $dateFrom) {
                $_SESSION['flash'] = 'Data preenchida invalida! (data futura)';
                $this->redirect('/config');
            }

            $updateFields['birthdate'] = $birthdate;

            // Validando e alterando a senha do usuário
            if (!empty($password)) {
                if ($password === $passwordConfirmation) {
                    $updateFields['birthdate'] = password_hash($password, PASSWORD_DEFAULT);;
                } else {
                    $_SESSION['flash'] = 'Senha não confere!';
                    $this->redirect('/config');
                }
            }

            //Campos normais

            $updateFields['name'] = $name;
            $updateFields['city'] = $city;
            $updateFields['work'] = $work;

            // avatar

            if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])) {
                $newAvatar = $_FILES['avatar'];
                if (in_array($newAvatar['type'], ['image/jpg', 'image/png', 'image/jpeg'])) {
                    $avatarName = $this->cutImage($newAvatar, 200, 200, 'media/avatars');
                    $updateFields['avatar'] = $avatarName;
                }
            }

            //cover

            if (isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])) {
                $newCover = $_FILES['cover'];
                if (in_array($newCover['type'], ['image/jpg', 'image/png', 'image/jpeg'])) {
                    $coverName = $this->cutImage($newCover, 850, 310, 'media/covers');
                    $updateFields['cover'] = $coverName;
                }
            }

            //acão de update
            UserHandler::updateUserInfo($updateFields, $this->loggedUser->id);
        }
        $this->redirect('/config');
    }

    private function cutImage($file, $w, $h, $folder)
    {
                
        list($widthOrig, $heightOrig) = getimagesize($file['tmp_name']);
        $ratio = $widthOrig / $heightOrig;

        $newWidth = $w;
        $newHeight = $newWidth / $ratio;

        if ($newHeight < $h) {
            $newHeight = $h;
            $newWidth = $newHeight * $ratio;
        }

        $x = $w - $newWidth;
        $y = $h - $newHeight;

        $x = $x < 0 ? $x / 2 : $x;
        $y = $y < 0 ? $y / 2 : $y;

        $finalImage = imagecreatetruecolor($w, $h);

        switch ($file['type']) {
            case 'image/jpg':
            case 'image/jpeg':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'image/png':
                $image = imagecreatefrompng($file['tmp_name']);
                break;
        }

        imagecopyresampled(
            $finalImage, $image,
            $x, $y, 0, 0,
            $newWidth, $newHeight,
            $widthOrig, $heightOrig
        );

        $fileName = md5(time() . rand(0, 9999) . time()) . '.jpg';

        imagejpeg($finalImage, $folder . '/' . $fileName, 100);
       
        return $fileName;
    }
}
