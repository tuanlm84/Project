<?php ob_start(); ?>
<link rel="stylesheet" href="/public/css/home.css">

<div class="container">
    <div class="header">
        <h2>Xin chào, <?= htmlspecialchars($user['email']) ?></h2>
        <a href="/auth/logout" class="logout-btn">Đăng xuất</a>
    </div>

    <form class="upload-form" action="/upload" method="POST" enctype="multipart/form-data">
        <label for="document">Chọn tệp cần kiểm tra đạo văn:</label>
        <input type="file" name="document" id="document" required>
        <button type="submit" class="btn btn-upload">Nộp bài</button>
    </form>

    <h3>Lịch sử bài đã nộp</h3>
    <table class="history-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Tên tệp</th>
            <th>Trạng thái</th>
            <th>Tiến độ</th>
            <th>Kết quả</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($jobs as $job): ?>
            <tr>
                <td><?= $job['id'] ?></td>
                <td><?= htmlspecialchars($job['filename']) ?></td>
                <td><?= $job['status'] ?></td>
                <td><?= $job['progress_percent'] ?>%</td>
                <td>
                    <?php if ($job['status'] === 'done'): ?>
                        <a href="/download?id=<?= $job['id'] ?>" class="btn btn-sm">Tải kết quả</a>
                    <?php elseif ($job['status'] === 'processing'): ?>
                        <span class="badge processing">Đang xử lý...</span>
                    <?php elseif ($job['status'] === 'failed'): ?>
                        <span class="badge failed">Lỗi</span>
                    <?php else: ?>
                        <span class="badge pending">Đang chờ</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
