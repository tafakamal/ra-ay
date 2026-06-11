<?php
/**
 * View Index Kategori Berita
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<div class="panel-card">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-category"></i> Kategori Berita</h2>
        <a href="<?= url('/admin/categories/create') ?>" class="btn btn-primary">
            <i class="ti ti-plus"></i> Tambah Kategori Baru
        </a>
    </div>

    <div class="panel-body">
        <?php if (empty($categories)): ?>
            <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                <i class="ti ti-category" style="font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p>Belum ada kategori yang ditambahkan.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 60px; text-align: center;">ID</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Warna Label</th>
                            <th style="text-align: center;">Urutan</th>
                            <th style="text-align: center;">Jumlah Berita</th>
                            <th style="text-align: center;">Status</th>
                            <th style="width: 150px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td style="text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                                    <?= $cat['id'] ?>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--secondary); display: flex; align-items: center; gap: 0.5rem;">
                                        <span style="display: inline-block; width: 12px; height: 12px; border-radius: 50%; background-color: <?= e($cat['color']) ?>;"></span>
                                        <?= e($cat['name']) ?>
                                    </div>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.9rem;">
                                    <?= e($cat['slug']) ?>
                                </td>
                                <td>
                                    <code style="background-color: var(--light); padding: 0.2rem 0.4rem; border-radius: 4px; font-size: 0.85rem; border: 1px solid var(--border);">
                                        <?= e($cat['color']) ?>
                                    </code>
                                </td>
                                <td style="text-align: center; font-weight: 500;">
                                    <?= $cat['sort_order'] ?>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-info" style="font-size: 0.8rem; font-weight: 700;">
                                        <?= $cat['news_count'] ?> Berita
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-<?= $cat['is_active'] ? 'success' : 'danger' ?>">
                                        <?= $cat['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: inline-flex; gap: 0.5rem;">
                                        <a href="<?= url('/admin/categories/edit/' . $cat['id']) ?>" class="btn btn-outline btn-sm" title="Edit Kategori">
                                            <i class="ti ti-edit" style="color: var(--primary);"></i>
                                        </a>
                                        <a href="<?= url('/admin/categories/delete/' . $cat['id']) ?>" 
                                           class="btn btn-outline btn-sm btn-danger-hover" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Berita yang terkait dengan kategori ini akan diset tanpa kategori.')" 
                                           title="Hapus Kategori"
                                           style="border-color: rgba(239, 68, 68, 0.2);">
                                            <i class="ti ti-trash" style="color: var(--danger);"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
