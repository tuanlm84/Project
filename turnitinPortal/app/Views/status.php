<?php
$title = 'Trạng thái bài nộp';
ob_start();
?>

<h4 class="text-primary mb-4">Trạng thái bài nộp #<?= $job['id'] ?></h4>

<ul class="list-group mb-3">
    <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($job['student_email']) ?></li>
    <li class="list-group-item"><strong>Tên file:</strong> <?= htmlspecialchars($job['filename']) ?></li>
    <li class="list-group-item"><strong>Trạng thái:</strong> <?= $job['status'] ?></li>
    <li class="list-group-item"><strong>Tiến độ:</strong> <?= $job['progress_percent'] ?>%</li>
    <li class="list-group-item"><strong>Kết quả:</strong>
        <?php if ($job['status'] === 'done'): ?>
            <a href="/download?id=<?= $job['id'] ?>" class="btn btn-sm btn-outline-primary">📥 Tải xuống kết quả</a>
            <div class="mt-2">Similarity: <strong><?= $job['similarity_score'] ?>%</strong></div>
        <?php elseif ($job['status'] === 'failed'): ?>
            <span class="text-danger">❌ Xử lý thất bại</span>
        <?php else: ?>
            <div class="spinner-border spinner-border-sm text-info" role="status"></div> Đang xử lý...
        <?php endif; ?>
    </li>
</ul>

<div class="d-flex gap-2">
    <a href="/" class="btn btn-secondary">⬅ Về trang chủ</a>
    <a href="/upload" class="btn btn-outline-primary">🔁 Nộp bài khác</a>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
