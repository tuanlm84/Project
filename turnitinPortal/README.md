# Turnitin Plagiarism Check Portal (HCMUTE)

Hệ thống kiểm tra đạo văn sử dụng PHP MVC + Google SSO + Redis + Python Worker + Turnitin API.

## Kiến trúc

- PHP MVC (Controllers / Models / Views)
- Đăng nhập qua Google SSO
- File được đẩy vào Redis Queue
- Python worker xử lý và gửi đến Turnitin
- Kết quả lưu vào JSON, trả về cho sinh viên

## Cấu hình

### 1. Cài đặt

```bash
git clone ...
cd turnitin-portal
composer install
