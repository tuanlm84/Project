<?php

namespace App\Models;

use PDO;

class Job
{
    protected $db;

    public function __construct()
    {
        // Đảm bảo .env đã được load (chỉ load nếu chưa có)
        if (!isset($_ENV['DB_HOST'])) {
            require_once __DIR__ . '/../../vendor/autoload.php';
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }

        // Nhúng PDO từ config/database.php
        $this->db = require __DIR__ . '/../../Config/database.php';
    }

    public function create($studentEmail, $filename)
    {
        $stmt = $this->db->prepare("
            INSERT INTO jobs (student_email, filename, status, created_at) 
            VALUES (?, ?, 'waiting', NOW())
        ");
        $stmt->execute([$studentEmail, $filename]);
        return $this->db->lastInsertId();
    }

    public function updateStatus($jobId, $status, $progress = null, $resultFile = null, $similarity = null)
    {
        $fields = ['status = :status'];
        $params = ['status' => $status, 'id' => $jobId];

        if ($progress !== null) {
            $fields[] = 'progress_percent = :progress';
            $params['progress'] = $progress;
        }

        if ($resultFile !== null) {
            $fields[] = 'result_file = :result_file';
            $params['result_file'] = $resultFile;
        }

        if ($similarity !== null) {
            $fields[] = 'similarity_score = :similarity';
            $params['similarity'] = $similarity;
        }

        $fields[] = 'updated_at = NOW()';
        $sql = "UPDATE jobs SET " . implode(', ', $fields) . " WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function findByStudent($email)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM jobs 
            WHERE student_email = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($jobId)
    {
        $stmt = $this->db->prepare("SELECT * FROM jobs WHERE id = ?");
        $stmt->execute([$jobId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmailAndFilename($email, $filename)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM jobs 
        WHERE student_email = ? AND filename = ? 
        ORDER BY created_at DESC 
        LIMIT 1
    ");
        $stmt->execute([$email, $filename]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($jobId)
    {
        $stmt = $this->db->prepare("DELETE FROM jobs WHERE id = ?");
        return $stmt->execute([$jobId]);
    }

}
