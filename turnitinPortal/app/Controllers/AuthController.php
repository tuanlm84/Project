<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\View;

class AuthController extends Controller
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../../Config/database.php';

            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                Session::set('user_email', $user['username']);
                $this->redirect('/');
            } else {
                $error = "Sai tên đăng nhập hoặc mật khẩu.";
                View::render('login', ['error' => $error]);
            }
        } else {
            View::render('login');
        }
    }

    public function google()
    {
        $google = new \App\Services\GoogleSSOService();
        $loginUrl = $google->getLoginUrl();
        $this->redirect($loginUrl);
    }

    public function callback()
    {
        $google = new \App\Services\GoogleSSOService();
        $userInfo = $google->handleCallback();

        if ($userInfo && isset($userInfo['email'])) {
            Session::set('user_email', $userInfo['email']);
            $this->redirect('/');
        } else {
            echo "Đăng nhập Google thất bại.";
        }
    }

    public function logout()
    {
        Session::destroy();
        $this->redirect('/auth/login');
    }
}
