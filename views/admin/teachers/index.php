<?php
/**
 * View Index Guru & Staf
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<!-- jQuery UI untuk Drag and Drop Reorder -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<div class="panel-card">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-users"></i> Profil Guru & Tenaga Pendidik</h2>
        <a href="<?= url('/admin/teachers/create') ?>" class="btn btn-primary">
            <i class="ti ti-user-plus"></i> Tambah Guru Baru
        </a>
    </div>

    <div class="panel-body">
        <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;">
            <i class="ti ti-info-circle" style="color: var(--primary);"></i> 
            Ubah urutan guru dengan **menyeret (drag & drop)** gagang ikon <i class="ti ti-selector"></i> di sebelah kiri nama guru. Urutan akan tersimpan otomatis.
        </p>

        <?php if (empty($teachers)): ?>
            <div style="text-align: center; padding: 4rem 1rem; color: var(--text-muted);">
                <i class="ti ti-users" style="font-size: 3.5rem; display: block; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p>Belum ada profil guru yang terdaftar.</p>
            </div>
        <?php else: ?>
            <!-- Teacher Sortable List Container -->
            <div id="teacher-list">
                <?php foreach ($teachers as $t): ?>
                    <div class="teacher-list-item" data-id="<?= $t['id'] ?>">
                        <!-- Drag Handle -->
                        <div class="teacher-drag-handle" style="cursor: grab; display: flex; align-items: center; padding: 0 0.5rem;">
                            <i class="ti ti-selector" style="font-size: 1.25rem;"></i>
                        </div>
                        
                        <!-- Teacher Photo -->
                        <img src="<?= upload_url($t['photo'], 'assets/images/placeholder.jpg') ?>" alt="Foto <?= e($t['name']) ?>" class="teacher-thumb">
                        
                        <!-- Teacher Info -->
                        <div class="teacher-info">
                            <div class="teacher-name"><?= e($t['name']) ?></div>
                            <div class="teacher-nip">
                                <?php if (!empty($t['nip'])): ?>
                                    NIP. <?= e($t['nip']) ?>
                                <?php else: ?>
                                    <span style="font-style: italic; color: #94A3B8;">Tidak ada NIP</span>
                                <?php endif; ?>
                                <span style="margin: 0 0.5rem; color: #CBD5E1;">|</span>
                                <span>Jabatan: <strong><?= e($t['position']) ?></strong></span>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div style="margin-right: 1.5rem;">
                            <span class="badge badge-<?= $t['is_active'] ? 'success' : 'danger' ?>">
                                <?= $t['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?= url('/admin/teachers/edit/' . $t['id']) ?>" class="btn btn-outline btn-sm" title="Edit Guru">
                                <i class="ti ti-edit" style="color: var(--primary);"></i>
                            </a>
                            <a href="<?= url('/admin/teachers/delete/' . $t['id']) ?>" 
                               class="btn btn-outline btn-sm" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus data guru ini?')" 
                               title="Hapus Guru"
                               style="border-color: rgba(239, 68, 68, 0.2);">
                                <i class="ti ti-trash" style="color: var(--danger);"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    $(function() {
        // Initialize jQuery UI Sortable
        $("#teacher-list").sortable({
            handle: '.teacher-drag-handle',
            placeholder: 'teacher-list-item', // CSS class for placeholder block while dragging
            start: function(e, ui) {
                // Style placeholder same height as dragging element
                ui.placeholder.css({
                    'height': ui.item.outerHeight(),
                    'background-color': '#F8FAFC',
                    'border': '2px dashed var(--primary)',
                    'visibility': 'visible',
                    'border-radius': '8px'
                });
            },
            update: function(event, ui) {
                // Dapatkan array ID terurut
                var orders = [];
                $("#teacher-list .teacher-list-item").each(function() {
                    orders.push($(this).data('id'));
                });

                // Kirim AJAX post ke route reorder
                $.ajax({
                    url: '<?= url('/admin/teachers/reorder') ?>',
                    method: 'POST',
                    data: JSON.stringify({ ids: orders }),
                    contentType: 'application/json',
                    success: function(response) {
                        // Flash message sukses tidak perlu full reload, cukup console log
                        console.log('Urutan guru diperbarui:', response);
                    },
                    error: function(xhr, status, error) {
                        alert('Gagal memperbarui urutan: ' + (xhr.responseJSON?.message || error));
                    }
                });
            }
        });
    });
</script>
