<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use DateTime;

class LoginController extends Controller
{

    public function signin()
    {
        $flash = '';
        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        $this->render('signin', [
            'flash' => $flash
        ]);
    }

    public function signinAction()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        if ($email && $password) {

            $token = UserHandler::verifyLogin($email, $password);

            if ($token) {
                $_SESSION['token'] = $token;
                $this->redirect('/');
            } else {
                $_SESSION['flash'] = 'Email e/ou Senha não conferem!';
                $this->redirect('/login');
            }
        } else {
            $_SESSION['flash'] = 'Digite os campos de Email e/ou Senha!';
            $this->redirect('/login');
        }
    }

    public function signup()
    {
        $flash = '';
        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        $this->render('signup', [
            'flash' => $flash
        ]);
    }

    public function signupAction()
    {
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $birthdate = filter_input(INPUT_POST, 'birthdate');

        if ($name && $email && $password && $birthdate) {
            $birthdate = explode('/', $birthdate);

            if (count($birthdate) != 3) {
                $_SESSION['flash'] = 'Data preenchida não confere. ex: 00/00/0000';
                $this->redirect('/cadastro');
            }


            $birthdate = $birthdate[2] . '-' . $birthdate[1] . '-' . $birthdate[0];

            if (strtotime($birthdate) === false) {
                $_SESSION['flash'] = 'Data preenchida não confere. ex: 00/00/0000';
                $this->redirect('/cadastro');
            }

            $dateFrom = new DateTime($birthdate);
            $limit = new DateTime('today');

            if ($limit < $dateFrom) {
                $_SESSION['flash'] = 'Data preenchida invalida! (data futura)';
                $this->redirect('/cadastro');
            }


            if (UserHandler::emailExists($email) === false) {
                $token = UserHandler::addUser($name, $email, $password, $birthdate);


                $_SESSION['token'] = $token;

                $this->redirect('/');
            }else{
                $_SESSION['flash'] = 'Email já existe';
                $this->redirect('/cadastro');
            }
        } else {
            $this->redirect('/cadastro');
        }
    }

    public function logout(){
        $_SESSION['token'] = '';
        $this->redirect('/login');
    }
}
