<?php

namespace App\Core;

class View

{
    public static function render($view, $data = [])
    {
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "View '$view' không tồn tại.";
            exit;
        }

        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        include __DIR__ . '/../Views/layout.php';
    }
}
