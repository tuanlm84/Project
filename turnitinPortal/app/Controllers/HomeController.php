<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\View;

class HomeController extends Controller
{
    public function index()
    {
        $logPath = '/var/www/html/debug/home.log';
        file_put_contents($logPath, "HomeController loaded\n", FILE_APPEND);

        if (!Session::has('user_email')) {
            file_put_contents($logPath, "User not logged in\n", FILE_APPEND);
            $this->redirect('/auth/login');
        }

        $userEmail = Session::get('user_email');
        file_put_contents($logPath, "Current user_email = $userEmail\n", FILE_APPEND);

        // Kết nối DB
        $pdo = require dirname(__DIR__, 2) . '/Config/database.php';

        // Truy vấn các job của user hiện tại
        $stmt = $pdo->prepare("SELECT * FROM jobs WHERE student_email = ? ORDER BY created_at DESC");
        $stmt->execute([$userEmail]);
        $jobs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        file_put_contents($logPath, "Loaded " . count($jobs) . " jobs\n", FILE_APPEND);

        View::render('home', [
            'user' => ['email' => $userEmail],
            'jobs' => $jobs
        ]);
    }

}
