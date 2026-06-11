<?php
/**
 * View Index Pesan Kontak
 * RA Attakal Yaqiin
 */

declare(strict_types=1);

$paginationUrl = function(int $pageNum): string {
    return url('/admin/contacts') . '?page=' . $pageNum;
};
?>

<div class="panel-card">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-mail"></i> Pesan Masuk dari Kontak Form</h2>
        
        <!-- Form Tandai Semua Sudah Dibaca -->
        <?php if ($unreadCount > 0): ?>
            <form action="<?= url('/admin/contacts/mark-all-read') ?>" method="POST" style="margin: 0;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Tandai semua pesan masuk sebagai sudah dibaca?')">
                    <i class="ti ti-mail-opened"></i> Tandai Semua Dibaca
                </button>
            </form>
        <?php endif; ?>
    </div>

    <div class="panel-body">
        <?php if (empty($contacts)): ?>
            <div style="text-align: center; padding: 4rem 1rem; color: var(--text-muted);">
                <i class="ti ti-mail-opened" style="font-size: 3.5rem; display: block; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p>Kotak masuk pesan kosong.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 180px;">Nama Pengirim</th>
                            <th>Subjek / Perihal</th>
                            <th style="width: 150px;">No. Telepon / HP</th>
                            <th style="width: 180px;">Tanggal Masuk</th>
                            <th style="width: 100px; text-align: center;">Status</th>
                            <th style="width: 130px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $c): ?>
                            <tr style="<?= !$c['is_read'] ? 'background-color: rgba(232, 96, 10, 0.02); font-weight: 500;' : '' ?>">
                                <td>
                                    <div style="color: var(--secondary); font-weight: 600;">
                                        <?= e($c['name']) ?>
                                    </div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                                        <?= e($c['email']) ?>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?= url('/admin/contacts/view/' . $c['id']) ?>" style="color: var(--secondary); font-weight: 600; hover: color: var(--primary);">
                                        <?= e($c['subject'] !== '' ? $c['subject'] : '(Tanpa Subjek)') ?>
                                    </a>
                                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">
                                        <?= e(truncate($c['message'], 90)) ?>
                                    </div>
                                </td>
                                <td>
                                    <?= e($c['phone'] !== '' ? $c['phone'] : '-') ?>
                                </td>
                                <td style="font-size: 0.9rem; color: var(--text-muted);">
                                    <div><?= format_date($c['created_at'], 'short') ?></div>
                                    <div style="font-size: 0.75rem; margin-top: 0.15rem; color: var(--primary); font-weight: 500;">
                                        <?= format_date($c['created_at'], 'relative') ?>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-<?= $c['is_read'] ? 'success' : 'danger' ?>">
                                        <?= $c['is_read'] ? 'Dibaca' : 'Baru' ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: inline-flex; gap: 0.5rem;">
                                        <a href="<?= url('/admin/contacts/view/' . $c['id']) ?>" class="btn btn-primary btn-sm" title="Baca Pesan">
                                            <i class="ti ti-mail-opened"></i> Detail
                                        </a>
                                        <a href="<?= url('/admin/contacts/delete/' . $c['id']) ?>" 
                                           class="btn btn-outline btn-sm" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')" 
                                           title="Hapus Pesan"
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
