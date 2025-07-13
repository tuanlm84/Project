<?php

namespace App\Models;

use PDO;

class Job
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = require __DIR__ . '/../../Config/database.php';
    }

    // 🔍 Tìm theo ID
    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM jobs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔍 Tìm theo email và tên file
    public function findByEmailAndFilename($email, $filename)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM jobs WHERE student_email = ? AND filename = ?");
        $stmt->execute([$email, $filename]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ➕ Tạo job mới
    public function create($email, $filename)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO jobs (student_email, filename, status, progress_percent, created_at, updated_at)
            VALUES (?, ?, 'waiting', 0, NOW(), NOW())
        ");
        $stmt->execute([$email, $filename]);
        return $this->pdo->lastInsertId();
    }

    // 🔄 Cập nhật trạng thái
    public function updateStatus($id, $status, $percent = 0, $resultFile = null, $score = null)
    {
        $stmt = $this->pdo->prepare("
            UPDATE jobs SET
                status = ?,
                progress_percent = ?,
                result_file = ?,
                similarity_score = ?,
                updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$status, $percent, $resultFile, $score, $id]);
    }

    // ❌ Xóa theo ID
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM jobs WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // 📄 Lấy tất cả job theo người dùng (nếu cần show lịch sử)
    public function getAllByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM jobs WHERE student_email = ? ORDER BY created_at DESC");
        $stmt->execute([$email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
