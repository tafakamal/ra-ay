<?php
/**
 * Layout Admin Panel
 * RA Attakal Yaqiin
 */

declare(strict_types=1);

// Pastikan user sudah login
if (!is_logged_in()) {
    redirect('/admin/login');
}

$currentUser = current_user();
$flash = get_flash();

// Dapatkan jumlah pesan belum dibaca untuk badge
$contactModel = new Contact();
$unreadCount = $contactModel->getUnreadCount();

// Tentukan menu aktif berdasarkan request URI
$requestUri = $_SERVER['REQUEST_URI'] ?? '/admin';
$currentPath = '/' . trim(parse_url($requestUri, PHP_URL_PATH), '/');

function is_active_menu(string $menuPath, string $currentPath): bool {
    if ($menuPath === '/admin' && $currentPath === '/admin') {
        return true;
    }
    if ($menuPath !== '/admin' && str_starts_with($currentPath, $menuPath)) {
        return true;
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Dashboard Admin') ?> - RA Attakal Yaqiin</title>
    <!-- Tabler Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <!-- jQuery (untuk reorder guru atau utility AJAX jika dibutuhkan) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <a href="<?= url('/admin') ?>" class="brand-logo">
                    <i class="ti ti-school"></i> RA Attakal Yaqiin<span>.</span>
                </a>
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-item <?= is_active_menu('/admin', $currentPath) && $currentPath === '/admin' ? 'active' : '' ?>">
                    <a href="<?= url('/admin') ?>" class="menu-link">
                        <i class="ti ti-layout-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-item <?= is_active_menu('/admin/news', $currentPath) ? 'active' : '' ?>">
                    <a href="<?= url('/admin/news') ?>" class="menu-link">
                        <i class="ti ti-news"></i>
                        <span>Kelola Berita</span>
                    </a>
                </li>
                <li class="menu-item <?= is_active_menu('/admin/categories', $currentPath) ? 'active' : '' ?>">
                    <a href="<?= url('/admin/categories') ?>" class="menu-link">
                        <i class="ti ti-category"></i>
                        <span>Kategori Berita</span>
                    </a>
                </li>
                <li class="menu-item <?= is_active_menu('/admin/pages', $currentPath) ? 'active' : '' ?>">
                    <a href="<?= url('/admin/pages') ?>" class="menu-link">
                        <i class="ti ti-file-text"></i>
                        <span>Kelola Halaman</span>
                    </a>
                </li>
                <li class="menu-item <?= is_active_menu('/admin/gallery', $currentPath) ? 'active' : '' ?>">
                    <a href="<?= url('/admin/gallery') ?>" class="menu-link">
                        <i class="ti ti-photo-album"></i>
                        <span>Galeri Foto</span>
                    </a>
                </li>
                <li class="menu-item <?= is_active_menu('/admin/teachers', $currentPath) ? 'active' : '' ?>">
                    <a href="<?= url('/admin/teachers') ?>" class="menu-link">
                        <i class="ti ti-users"></i>
                        <span>Profil Guru</span>
                    </a>
                </li>
                <li class="menu-item <?= is_active_menu('/admin/contacts', $currentPath) ? 'active' : '' ?>">
                    <a href="<?= url('/admin/contacts') ?>" class="menu-link">
                        <i class="ti ti-mail"></i>
                        <span>Pesan Masuk</span>
                        <?php if ($unreadCount > 0): ?>
                            <span class="menu-badge"><?= $unreadCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="menu-item <?= is_active_menu('/admin/settings', $currentPath) ? 'active' : '' ?>">
                    <a href="<?= url('/admin/settings') ?>" class="menu-link">
                        <i class="ti ti-settings"></i>
                        <span>Pengaturan Website</span>
                    </a>
                </li>
                <li class="menu-item <?= is_active_menu('/admin/profile', $currentPath) ? 'active' : '' ?>">
                    <a href="<?= url('/admin/profile') ?>" class="menu-link">
                        <i class="ti ti-user-circle"></i>
                        <span>Profil Akun</span>
                    </a>
                </li>
                <li class="menu-item" style="margin-top: 2rem;">
                    <a href="<?= url('/admin/logout') ?>" class="menu-link text-danger" style="color: #F87171;" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                        <i class="ti ti-logout"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <div class="footer-user">
                    <img src="<?= upload_url($currentUser['avatar'] ?? '', 'assets/images/placeholder.jpg') ?>" alt="Avatar" class="footer-avatar">
                    <div class="footer-user-info">
                        <div class="footer-user-name" title="<?= e($currentUser['name'] ?? 'Admin') ?>"><?= e($currentUser['name'] ?? 'Admin') ?></div>
                        <div class="footer-user-role"><?= e(ucfirst($currentUser['role'] ?? 'admin')) ?></div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="admin-main">
            <!-- Top Header Bar -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="ti ti-menu-2"></i>
                    </button>
                    <h1 class="page-title"><?= e($title ?? 'Dashboard Admin') ?></h1>
                </div>
                <div class="header-right">
                    <a href="<?= url('/') ?>" target="_blank" class="view-site-btn">
                        <i class="ti ti-external-link"></i>
                        <span>Lihat Website</span>
                    </a>
                    <div class="header-user-profile">
                        <img src="<?= upload_url($currentUser['avatar'] ?? '', 'assets/images/placeholder.jpg') ?>" alt="Avatar" class="header-avatar">
                        <span style="font-weight: 500; font-size: 0.95rem; color: var(--secondary);"><?= e($currentUser['name'] ?? 'Admin') ?></span>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="admin-content">
                <!-- Flash Messages -->
                <?php if ($flash): ?>
                    <div class="alert alert-<?= $flash['type'] === 'error' || $flash['type'] === 'danger' ? 'danger' : 'success' ?>" id="flashAlert">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ti ti-<?= $flash['type'] === 'error' || $flash['type'] === 'danger' ? 'alert-triangle' : 'circle-check' ?>" style="font-size: 1.25rem;"></i>
                            <span><?= e($flash['message']) ?></span>
                        </div>
                        <button class="alert-close" onclick="document.getElementById('flashAlert').style.display='none';">&times;</button>
                    </div>
                <?php endif; ?>

                <!-- Content Slot -->
                <?= $content ?>
            </main>
        </div>
    </div>

    <!-- Toggle Sidebar Script on Mobile -->
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const adminSidebar = document.getElementById('adminSidebar');

        if (sidebarToggle && adminSidebar) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                adminSidebar.classList.toggle('show');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 992 && !adminSidebar.contains(e.target) && e.target !== sidebarToggle && !sidebarToggle.contains(e.target)) {
                    adminSidebar.classList.remove('show');
                }
            });
        }
        
        // Auto close flash message after 5 seconds
        const flashAlert = document.getElementById('flashAlert');
        if (flashAlert) {
            setTimeout(() => {
                flashAlert.style.transition = 'opacity 0.5s ease';
                flashAlert.style.opacity = '0';
                setTimeout(() => flashAlert.style.display = 'none', 500);
            }, 5000);
        }
    </script>
</body>
</html>
