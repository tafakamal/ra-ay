<?php
/**
 * View Edit Profil Guru
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<div class="panel-card" style="max-width: 700px; margin: 0 auto;">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-edit"></i> Edit Guru: <?= e($teacher['name']) ?></h2>
        <a href="<?= url('/admin/teachers') ?>" class="btn btn-outline">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="panel-body">
        <form action="<?= url('/admin/teachers/update/' . $teacher['id']) ?>" method="POST" enctype="multipart/form-data" id="teacherForm">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap Guru <span style="color: var(--danger);">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Aisyah Humaira, S.Pd." value="<?= e($teacher['name']) ?>" required>
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label for="nip" class="form-label">NIP / Nomor Induk Pegawai (Opsional)</label>
                    <input type="text" name="nip" id="nip" class="form-control" placeholder="Contoh: 19900101XXXXXXXX" value="<?= e($teacher['nip'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="position" class="form-label">Jabatan / Peran <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="position" id="position" class="form-control" placeholder="Contoh: Kepala Sekolah, Guru Kelas B, Staf TU" value="<?= e($teacher['position']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="photo" class="form-label">Foto Guru</label>
                <input type="file" name="photo" id="photo" class="form-control-file" accept="image/*" onchange="previewImage(event)">
                <span class="form-text">Format: JPG, JPEG, PNG, WEBP. Ukuran Maksimal 5MB. Kosongkan jika tidak ingin mengganti foto.</span>
                
                <div style="margin-top: 1rem;">
                    <!-- Foto saat ini -->
                    <?php if (!empty($teacher['photo'])): ?>
                        <div id="oldPhotoContainer" style="margin-bottom: 0.5rem;">
                            <span style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Foto Saat Ini:</span>
                            <img src="<?= upload_url($teacher['photo']) ?>" alt="Foto Guru" style="max-width: 150px; height: auto; border-radius: 8px; border: 1px solid var(--border);">
                        </div>
                    <?php endif; ?>

                    <!-- Preview foto baru -->
                    <div id="newPreviewContainer" style="display: none;">
                        <span style="font-size: 0.8rem; color: var(--primary); display: block; margin-bottom: 0.25rem;">Foto Baru (Preview):</span>
                        <img id="imagePreview" src="#" alt="Preview Foto" style="max-width: 150px; height: auto; border-radius: 8px; border: 1px solid var(--border);">
                    </div>
                </div>
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label for="is_active" class="form-label">Status Keaktifan</label>
                    <select name="is_active" id="is_active" class="form-select">
                        <option value="1" <?= $teacher['is_active'] ? 'selected' : '' ?>>Aktif (Tampilkan di Halaman Profil)</option>
                        <option value="0" <?= !$teacher['is_active'] ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="sort_order" class="form-label">Nomor Urutan Tampilan</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="<?= $teacher['sort_order'] ?>" min="0">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Simpan Perubahan
                </button>
                <a href="<?= url('/admin/teachers') ?>" class="btn btn-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview Image Upload
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
            
            document.getElementById('newPreviewContainer').style.display = 'block';
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
