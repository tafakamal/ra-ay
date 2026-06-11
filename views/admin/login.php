<?php
/**
 * View Login Admin
 * RA Attakal Yaqiin
 */

declare(strict_types=1);

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - RA Attakal Yaqiin</title>
    <!-- Tabler Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="login-body">
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="ti ti-school"></i>
                </div>
                <h2 class="login-title">RA Attakal Yaqiin</h2>
                <p class="login-subtitle">Panel Admin Website Sekolah</p>
            </div>

            <!-- Flash Messages -->
            <?php if ($flash): ?>
                <div class="alert alert-danger" id="flashAlert">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="ti ti-alert-triangle"></i>
                        <span style="font-size: 0.85rem; font-weight: 500;"><?= e($flash['message']) ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?= url('/admin/login') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div style="position: relative;">
                        <i class="ti ti-mail" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" required style="padding-left: 2.5rem;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div style="position: relative;">
                        <i class="ti ti-lock" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required style="padding-left: 2.5rem;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem; height: 45px; font-size: 1rem; border-radius: 8px;">
                    <i class="ti ti-login"></i> Masuk ke Panel
                </button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="<?= url('/') ?>" style="font-size: 0.85rem; color: var(--text-muted); display: inline-flex; align-items: center; gap: 0.25rem; hover: text-decoration: underline;">
                    <i class="ti ti-arrow-back-up"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto close alert
        const alert = document.getElementById('flashAlert');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.style.display = 'none', 500);
            }, 4000);
        }
    </script>
</body>
</html>
