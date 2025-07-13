<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Turnitin Portal</title>
    <link rel="stylesheet" href="/public/css/login.css">
</head>

<body>
<div class="container">
    <div class="logo">
        <img src="/public/logo.png" alt="HCMUTE Logo">
    </div>
    <h2>Hệ thống check đạo văn</h2>

    <?php if (!empty($error)): ?>
        <div class="error-message">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/auth/login">
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit" class="btn btn-login">Đăng nhập</button>
    </form>

    <form method="get" action="/auth/google">
        <button type="submit" class="btn btn-google">Đăng nhập với Google</button>
    </form>
</div>
</body>
</html>
