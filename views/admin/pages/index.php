<?php
/**
 * View Index Halaman Statis
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<div class="panel-card">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-file-text"></i> Kelola Halaman Statis</h2>
    </div>

    <div class="panel-body">
        <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;">
            Berikut adalah daftar halaman statis yang digunakan di website sekolah. Anda dapat mengubah isi konten, judul, dan pengaturan SEO masing-masing halaman.
        </p>

        <?php if (empty($pages)): ?>
            <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                <i class="ti ti-file-off" style="font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p>Tidak ada halaman statis yang terdaftar.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 60px; text-align: center;">ID</th>
                            <th>Judul Halaman</th>
                            <th>Slug / URL</th>
                            <th style="text-align: center;">Status</th>
                            <th>Terakhir Diperbarui</th>
                            <th style="width: 120px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $p): ?>
                            <tr>
                                <td style="text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                                    <?= $p['id'] ?>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--secondary);">
                                        <?= e($p['title']) ?>
                                    </div>
                                </td>
                                <td>
                                    <code style="background-color: var(--light); padding: 0.2rem 0.4rem; border-radius: 4px; font-size: 0.85rem; border: 1px solid var(--border);">
                                        /<?= e($p['slug']) ?>
                                    </code>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-<?= $p['status'] === 'published' ? 'success' : 'warning' ?>">
                                        <?= $p['status'] === 'published' ? 'Terbit' : 'Draft' ?>
                                    </span>
                                </td>
                                <td style="font-size: 0.9rem; color: var(--text-muted);">
                                    <?= format_date($p['updated_at'] ?? $p['created_at'], 'datetime') ?>
                                </td>
                                <td style="text-align: center;">
                                    <a href="<?= url('/admin/pages/edit/' . $p['id']) ?>" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                        <i class="ti ti-edit" style="color: var(--primary);"></i> Edit Konten
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
