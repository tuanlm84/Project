<?php

namespace App\Core;

class Controller
{
    protected function view($viewName, $data = [])
    {
        extract($data);
        $viewPath = __DIR__ . '/../Views/' . $viewName . '.php';

        if (!file_exists($viewPath)) {
            echo "View $viewName không tồn tại.";
            return;
        }

        // Bắt đầu lưu nội dung view vào biến $content
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        // Gọi layout và truyền $content
        include __DIR__ . '/../Views/layout.php';
    }

    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    protected function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isLoggedIn()
    {
        return isset($_SESSION['user_email']);
    }

    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/auth/login');
        }
    }
}
