<?php
/**
 * View Index Berita
 * RA Attakal Yaqiin
 */

declare(strict_types=1);

// Helper untuk generate URL pagination dengan query string yang dipertahankan
$paginationUrl = function(int $pageNum) use ($search, $categoryId): string {
    $params = ['page' => $pageNum];
    if ($search !== '') {
        $params['q'] = $search;
    }
    if ($categoryId !== '') {
        $params['category_id'] = $categoryId;
    }
    return url('/admin/news') . '?' . http_build_query($params);
};
?>

<div class="panel-card">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-news"></i> Daftar Berita & Artikel</h2>
        <a href="<?= url('/admin/news/create') ?>" class="btn btn-primary">
            <i class="ti ti-plus"></i> Tambah Berita Baru
        </a>
    </div>
    
    <div class="panel-body">
        <!-- Search & Filter Form -->
        <form action="<?= url('/admin/news') ?>" method="GET" style="margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                <label for="q" class="form-label">Cari Berita</label>
                <div style="position: relative;">
                    <i class="ti ti-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="text" name="q" id="q" class="form-control" placeholder="Ketik judul atau kata kunci..." value="<?= e($search) ?>" style="padding-left: 2.5rem;">
                </div>
            </div>
            
            <div class="form-group" style="width: 200px; margin-bottom: 0;">
                <label for="category_id" class="form-label">Filter Kategori</label>
                <select name="category_id" id="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (string)$categoryId === (string)$cat['id'] ? 'selected' : '' ?>>
                            <?= e($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-secondary">
                    <i class="ti ti-filter"></i> Filter
                </button>
                <?php if ($search !== '' || $categoryId !== ''): ?>
                    <a href="<?= url('/admin/news') ?>" class="btn btn-outline">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <!-- News Table -->
        <?php if (empty($newsList)): ?>
            <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                <i class="ti ti-news-off" style="font-size: 3rem; display: block; margin-bottom: 1rem;"></i>
                <p>Tidak ada berita yang ditemukan.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Gambar</th>
                            <th>Berita / Judul</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tipe</th>
                            <th>Tanggal Buat</th>
                            <th style="width: 150px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($newsList as $item): ?>
                            <tr>
                                <td>
                                    <img src="<?= upload_url($item['featured_image'], 'assets/images/placeholder.jpg') ?>" 
                                         alt="Image" 
                                         style="width: 60px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border);">
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--secondary); font-size: 0.95rem;">
                                        <?= e($item['title']) ?>
                                    </div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem; display: flex; align-items: center; gap: 0.5rem;">
                                        <span><i class="ti ti-user" style="font-size: 0.9rem;"></i> <?= e($item['author_name'] ?? 'Admin') ?></span>
                                        <span>•</span>
                                        <span><i class="ti ti-eye" style="font-size: 0.9rem;"></i> <?= $item['views'] ?> views</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: var(--primary-light); color: var(--primary);">
                                        <?= e($item['category_name'] ?? 'Umum') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $item['status'] === 'published' ? 'success' : 'warning' ?>">
                                        <?= $item['status'] === 'published' ? 'Terbit' : 'Draft' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($item['is_featured']): ?>
                                        <span class="badge badge-info"><i class="ti ti-star"></i> Unggulan</span>
                                    <?php else: ?>
                                        <span class="badge badge-outline" style="border: 1px solid var(--border); color: var(--text-muted);">Biasa</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-size: 0.9rem;">
                                    <?= format_date($item['created_at'], 'short') ?>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: inline-flex; gap: 0.5rem;">
                                        <a href="<?= url('/admin/news/edit/' . $item['id']) ?>" class="btn btn-outline btn-sm" title="Edit Berita">
                                            <i class="ti ti-edit" style="color: var(--primary);"></i>
                                        </a>
                                        <a href="<?= url('/admin/news/delete/' . $item['id']) ?>" 
                                           class="btn btn-outline btn-sm btn-danger-hover" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')" 
                                           title="Hapus Berita"
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

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Menampilkan Halaman <strong><?= $page ?></strong> dari <strong><?= $totalPages ?></strong> Halaman
                    </div>
                    <ul class="pagination">
                        <!-- Previous Link -->
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $page <= 1 ? '#' : $paginationUrl($page - 1) ?>">
                                <i class="ti ti-chevron-left"></i>
                            </a>
                        </li>
                        
                        <!-- Page Links -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $page === $i ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $paginationUrl($i) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next Link -->
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $page >= $totalPages ? '#' : $paginationUrl($page + 1) ?>">
                                <i class="ti ti-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
