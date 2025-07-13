<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\View;
use App\Models\Job;
use App\Services\QueueService;

class UploadController extends Controller
{
    public function index()
    {
        $this->requireLogin();
        $error = $_GET['error'] ?? null;
        View::render('upload', [
            'title' => 'Nộp bài',
            'error' => $error
        ]);
    }

    public function submit()
    {
        $this->requireLogin();
        $logPath = '/var/www/html/debug/upload.log';
        $email = Session::get('user_email');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

        // Ghi log bắt đầu
        file_put_contents($logPath, "=== Upload bắt đầu @ " . date('Y-m-d H:i:s') . " ===\n", FILE_APPEND);
        file_put_contents($logPath, "Từ email: $email, IP: $ip\n", FILE_APPEND);
        file_put_contents($logPath, "FILES: " . print_r($_FILES, true), FILE_APPEND);

        // Xác định key file upload
        $fieldKey = array_keys($_FILES)[0] ?? null;
        file_put_contents($logPath, "FIELD KEY: $fieldKey\n", FILE_APPEND);

        $file = $fieldKey ? $_FILES[$fieldKey] : null;

        // Kiểm tra lỗi hệ thống PHP upload
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $errorCode = $file['error'] ?? 'N/A';
            file_put_contents($logPath, "❌ Lỗi upload: error code = $errorCode\n", FILE_APPEND);

            if ($errorCode == UPLOAD_ERR_INI_SIZE) {
                $count = Session::get('upload_failures') ?? 0;
                $count++;
                Session::set('upload_failures', $count);

                if ($count >= 5) {
                    Session::forget('user_email');
                    Session::forget('upload_failures');
                    $this->redirect('/auth/logout');
                }

                $this->redirect('/upload?error=size');
            }

            $this->redirect('/upload?error=1');
        }

        // Reset số lần thất bại nếu thành công
        Session::forget('upload_failures');

        // Kiểm tra định dạng file
        $originalName = $file['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['doc', 'docx', 'pdf'];

        if (!in_array($ext, $allowed)) {
            file_put_contents($logPath, "❌ File không hợp lệ: $originalName\n", FILE_APPEND);
            $this->redirect('/upload?error=type');
        }

        $tmpPath = $file['tmp_name'];
        $cleanName = $this->cleanFilename($originalName);

        // Chuẩn bị thư mục upload
        $uploadDir = __DIR__ . '/../../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $finalPath = $uploadDir . $cleanName;

        // Xóa file cũ nếu trùng tên
        if (file_exists($finalPath)) {
            unlink($finalPath);
        }

        // Xoá job cũ nếu đã tồn tại
        $jobModel = new Job();
        $existingJob = $jobModel->findByEmailAndFilename($email, $cleanName);
        if ($existingJob) {
            $jobModel->delete($existingJob['id']);
            if (!empty($existingJob['result_file'])) {
                $resultPath = $uploadDir . $existingJob['result_file'];
                if (file_exists($resultPath)) {
                    unlink($resultPath);
                }
            }
        }

        // Lưu file lên server
        if (!move_uploaded_file($tmpPath, $finalPath)) {
            file_put_contents($logPath, "❌ Không thể move_uploaded_file\n", FILE_APPEND);
            $this->redirect('/upload?error=2');
        }

        // Tạo job mới
        $jobId = $jobModel->create($email, $cleanName);

        // Gửi vào hàng đợi
        $queue = new QueueService();
        $queue->pushJob([
            'job_id' => $jobId,
            'filename' => $cleanName,
            'email' => $email
        ]);

        // Điều hướng tới trạng thái
        $this->redirect('/status/' . $jobId);
    }

    private function cleanFilename($filename)
    {
        // Bỏ dấu tiếng Việt
        $filename = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $filename);
        // Thay ký tự đặc biệt bằng _
        $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $filename);
        // Giới hạn độ dài
        return substr($filename, 0, 100);
    }
}
