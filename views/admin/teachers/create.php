<?php
/**
 * View Tambah Guru Baru
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<div class="panel-card" style="max-width: 700px; margin: 0 auto;">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-user-plus"></i> Tambah Profil Guru Baru</h2>
        <a href="<?= url('/admin/teachers') ?>" class="btn btn-outline">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="panel-body">
        <form action="<?= url('/admin/teachers/store') ?>" method="POST" enctype="multipart/form-data" id="teacherForm">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap Guru <span style="color: var(--danger);">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Aisyah Humaira, S.Pd." required>
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label for="nip" class="form-label">NIP / Nomor Induk Pegawai (Opsional)</label>
                    <input type="text" name="nip" id="nip" class="form-control" placeholder="Contoh: 19900101XXXXXXXX">
                </div>

                <div class="form-group">
                    <label for="position" class="form-label">Jabatan / Peran <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="position" id="position" class="form-control" placeholder="Contoh: Kepala Sekolah, Guru Kelas B, Staf TU" required>
                </div>
            </div>

            <div class="form-group">
                <label for="photo" class="form-label">Foto Guru</label>
                <input type="file" name="photo" id="photo" class="form-control-file" accept="image/*" onchange="previewImage(event)">
                <span class="form-text">Format: JPG, JPEG, PNG, WEBP. Ukuran Maksimal 5MB.</span>
                
                <div style="margin-top: 1rem;">
                    <img id="imagePreview" src="#" alt="Preview Foto" style="max-width: 150px; height: auto; border-radius: 8px; border: 1px solid var(--border); display: none;">
                </div>
            </div>

            <div class="form-group">
                <label for="is_active" class="form-label">Status Keaktifan</label>
                <select name="is_active" id="is_active" class="form-select">
                    <option value="1" selected>Aktif (Tampilkan di Halaman Profil)</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Simpan Profil
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
            output.style.display = 'block';
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
