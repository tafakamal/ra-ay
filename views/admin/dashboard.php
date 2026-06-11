<?php
/**
 * View Dashboard Admin
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<!-- Overview Cards Grid -->
<div class="dashboard-grid">
    <div class="stat-card" style="border-left-color: #E8600A;">
        <div class="stat-info">
            <h3>Total Berita</h3>
            <div class="stat-number"><?= $counts['news'] ?></div>
        </div>
        <div class="stat-icon" style="color: #E8600A; background-color: #FFF7ED;">
            <i class="ti ti-news"></i>
        </div>
    </div>

    <div class="stat-card" style="border-left-color: #3B82F6;">
        <div class="stat-info">
            <h3>Kategori Berita</h3>
            <div class="stat-number"><?= $counts['categories'] ?></div>
        </div>
        <div class="stat-icon" style="color: #3B82F6; background-color: #E1EFFE;">
            <i class="ti ti-category"></i>
        </div>
    </div>

    <div class="stat-card" style="border-left-color: #10B981;">
        <div class="stat-info">
            <h3>Profil Guru</h3>
            <div class="stat-number"><?= $counts['teachers'] ?></div>
        </div>
        <div class="stat-icon" style="color: #10B981; background-color: #DEF7EC;">
            <i class="ti ti-users"></i>
        </div>
    </div>

    <div class="stat-card" style="border-left-color: #8B5CF6;">
        <div class="stat-info">
            <h3>Galeri Foto</h3>
            <div class="stat-number"><?= $counts['gallery'] ?></div>
        </div>
        <div class="stat-icon" style="color: #8B5CF6; background-color: #F3E8FF;">
            <i class="ti ti-photo-album"></i>
        </div>
    </div>

    <div class="stat-card" style="border-left-color: #EF4444;">
        <div class="stat-info">
            <h3>Pesan Belum Dibaca</h3>
            <div class="stat-number"><?= $counts['unread_contacts'] ?></div>
        </div>
        <div class="stat-icon" style="color: #EF4444; background-color: #FDE8E8;">
            <i class="ti ti-mail-unread"></i>
        </div>
    </div>
</div>

<!-- Quick Action Buttons -->
<div class="panel-card" style="margin-bottom: 2rem;">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-bolt" style="color: var(--primary);"></i> Akses Cepat</h2>
    </div>
    <div class="panel-body" style="display: flex; flex-wrap: wrap; gap: 1rem;">
        <a href="<?= url('/admin/news/create') ?>" class="btn btn-primary">
            <i class="ti ti-plus"></i> Tambah Berita
        </a>
        <a href="<?= url('/admin/gallery/create') ?>" class="btn btn-secondary">
            <i class="ti ti-photo-plus"></i> Tambah Foto/Video
        </a>
        <a href="<?= url('/admin/teachers/create') ?>" class="btn btn-outline" style="color: var(--secondary); border-color: var(--secondary);">
            <i class="ti ti-user-plus"></i> Tambah Guru
        </a>
        <a href="<?= url('/admin/settings') ?>" class="btn btn-outline">
            <i class="ti ti-settings"></i> Pengaturan Web
        </a>
    </div>
</div>

<div class="form-row form-row-2">
    <!-- Recent News -->
    <div class="panel-card">
        <div class="panel-header">
            <h2 class="panel-title"><i class="ti ti-news" style="color: var(--primary);"></i> Berita Terbaru</h2>
            <a href="<?= url('/admin/news') ?>" class="btn btn-outline btn-sm">Semua Berita</a>
        </div>
        <div class="panel-body" style="padding: 0;">
            <?php if (empty($latestNews)): ?>
                <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
                    <i class="ti ti-news-off" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;"></i>
                    Belum ada berita yang diterbitkan.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($latestNews as $news): ?>
                                <tr>
                                    <td>
                                        <a href="<?= url('/admin/news/edit/' . $news['id']) ?>" style="font-weight: 600; color: var(--secondary); hover: color: var(--primary);">
                                            <?= e(truncate($news['title'], 40)) ?>
                                        </a>
                                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                                            <?= format_date($news['created_at'], 'short') ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: var(--primary-light); color: var(--primary);">
                                            <?= e($news['category_name'] ?? 'Umum') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $news['status'] === 'published' ? 'success' : 'warning' ?>">
                                            <?= e($news['status'] === 'published' ? 'Terbit' : 'Draft') ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Messages -->
    <div class="panel-card">
        <div class="panel-header">
            <h2 class="panel-title"><i class="ti ti-mail" style="color: var(--primary);"></i> Pesan Masuk Terbaru</h2>
            <a href="<?= url('/admin/contacts') ?>" class="btn btn-outline btn-sm">Semua Pesan</a>
        </div>
        <div class="panel-body" style="padding: 0;">
            <?php if (empty($latestContacts)): ?>
                <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
                    <i class="ti ti-mail-opened" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;"></i>
                    Tidak ada pesan masuk.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Pengirim</th>
                                <th>Subjek</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($latestContacts as $contact): ?>
                                <tr>
                                    <td>
                                        <a href="<?= url('/admin/contacts/view/' . $contact['id']) ?>" style="font-weight: 600; color: var(--secondary);">
                                            <?= e($contact['name']) ?>
                                        </a>
                                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                                            <?= format_date($contact['created_at'], 'relative') ?>
                                        </div>
                                    </td>
                                    <td><?= e(truncate($contact['subject'], 30)) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $contact['is_read'] ? 'success' : 'danger' ?>">
                                            <?= $contact['is_read'] ? 'Dibaca' : 'Baru' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
