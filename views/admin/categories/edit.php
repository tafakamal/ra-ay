<?php
/**
 * View Edit Kategori Berita
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<div class="panel-card" style="max-width: 600px; margin: 0 auto;">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-edit"></i> Edit Kategori: <?= e($category['name']) ?></h2>
        <a href="<?= url('/admin/categories') ?>" class="btn btn-outline">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="panel-body">
        <form action="<?= url('/admin/categories/update/' . $category['id']) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="name" class="form-label">Nama Kategori <span style="color: var(--danger);">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Kegiatan Sekolah, Pengumuman, Akademik" value="<?= e($category['name']) ?>" required>
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label for="color" class="form-label">Warna Label / Badge</label>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <input type="color" name="color" id="color" class="form-control" value="<?= e($category['color'] ?? '#E8600A') ?>" style="width: 60px; padding: 0.2rem; height: 38px; cursor: pointer;">
                        <input type="text" id="color_text" class="form-control" placeholder="#E8600A" value="<?= e($category['color'] ?? '#E8600A') ?>" readonly>
                    </div>
                    <span class="form-text">Pilih warna untuk membedakan label kategori berita.</span>
                </div>

                <div class="form-group">
                    <label for="sort_order" class="form-label">Urutan Tampilan</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="<?= $category['sort_order'] ?>" min="0">
                    <span class="form-text">Semakin kecil angkanya, semakin awal ditampilkan.</span>
                </div>
            </div>

            <div class="form-group">
                <label for="is_active" class="form-label">Status Kategori</label>
                <select name="is_active" id="is_active" class="form-select">
                    <option value="1" <?= $category['is_active'] ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= !$category['is_active'] ? 'selected' : '' ?>>Nonaktif</option>
                </select>
                <span class="form-text">Kategori nonaktif tidak akan muncul di filter halaman berita website.</span>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Simpan Perubahan
                </button>
                <a href="<?= url('/admin/categories') ?>" class="btn btn-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Sinkronisasi color picker ke text input
    const colorPicker = document.getElementById('color');
    const colorText = document.getElementById('color_text');
    
    if (colorPicker && colorText) {
        colorPicker.addEventListener('input', (e) => {
            colorText.value = e.target.value.toUpperCase();
        });
    }
</script>
