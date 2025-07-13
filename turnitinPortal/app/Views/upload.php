<?php echo "<!-- Loaded upload.php -->"; ?>
<div class="d-flex justify-content-center align-items-center" style="min-height: 90vh;">
    <div class="card p-4 shadow rounded-4" style="max-width: 500px; width: 100%; background-color: #ffffff;">
        <div class="text-center mb-3">
            <img src="/public/logo.png" alt="Logo" style="height: 60px;">
        </div>

        <h4 class="text-center text-primary mb-4">Nộp tài liệu kiểm tra đạo văn</h4>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center">
                <?php
                switch ($error) {
                    case '1':
                        echo 'Lỗi khi tải lên tệp. Vui lòng thử lại.';
                        break;
                    case '2':
                        echo 'Không thể lưu tệp. Kiểm tra quyền thư mục.';
                        break;
                    case 'size':
                        echo 'File vượt quá dung lượng cho phép. Vui lòng chọn file nhỏ hơn.';
                        break;
                    case 'type':
                        echo 'Chỉ chấp nhận file .doc, .docx hoặc .pdf.';
                        break;
                    default:
                        echo 'Đã xảy ra lỗi không xác định.';
                }
                ?>
            </div>
        <?php endif; ?>

        <form action="/upload" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="file" name="file" required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">📤 Nộp bài</button>
        </form>

        <div class="text-center mt-3">
            <a href="/" class="btn btn-outline-secondary btn-sm">⬅ Về trang chủ</a>
        </div>
    </div>
</div>
