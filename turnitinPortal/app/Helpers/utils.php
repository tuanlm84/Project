<?php

namespace App\Helpers;

/**
 * Làm sạch tên file để tránh lỗi hoặc ký tự nguy hiểm.
 */
function sanitizeFileName($filename)
{
    // Loại bỏ đường dẫn, ký tự đặc biệt, giữ lại chữ, số, dấu gạch
    $filename = basename($filename);
    $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $filename);
    return $filename;
}

/**
 * Kiểm tra địa chỉ email hợp lệ.
 */
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Định dạng ngày giờ kiểu đẹp.
 */
function formatDatetime($datetime)
{
    $ts = strtotime($datetime);
    return date('H:i d/m/Y', $ts);
}

/**
 * Sinh tên file kết quả dựa theo tên file gốc.
 * Ví dụ: "baitap1.docx" -> "baitap1_result.pdf"
 */
function getResultFilename($filename)
{
    $name = pathinfo($filename, PATHINFO_FILENAME);
    return $name . '_result.pdf';
}

/**
 * Sinh UID ngắn (mã job, v.v.)
 */
function generateShortId($length = 8)
{
    return substr(md5(uniqid(mt_rand(), true)), 0, $length);
}
