<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Job;

class DownloadController extends Controller
{
    public function index()
    {
        // Kiểm tra tham số truyền vào
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            http_response_code(400);
            echo "Thiếu hoặc sai mã job.";
            exit;
        }

        $jobId = (int) $_GET['id'];
        $jobModel = new Job();
        $job = $jobModel->findById($jobId);

        if (!$job || $job['status'] !== 'done') {
            http_response_code(404);
            echo "Không tìm thấy kết quả hoặc chưa hoàn tất.";
            exit;
        }

        $filepath = __DIR__ . '/../../results/' . $job['result_file'];

        if (!file_exists($filepath)) {
            http_response_code(404);
            echo "File kết quả không tồn tại.";
            exit;
        }

        // Gửi header tải file
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
}
