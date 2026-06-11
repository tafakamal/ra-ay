<?php
/**
 * View Tambah Galeri Baru
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<div class="panel-card" style="max-width: 700px; margin: 0 auto;">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-photo-album"></i> Tambah Item Galeri Baru</h2>
        <a href="<?= url('/admin/gallery') ?>" class="btn btn-outline">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="panel-body">
        <form action="<?= url('/admin/gallery/store') ?>" method="POST" enctype="multipart/form-data" id="galleryForm">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="type" class="form-label">Tipe Galeri <span style="color: var(--danger);">*</span></label>
                <select name="type" id="type" class="form-select">
                    <option value="photo" selected>Foto / Gambar</option>
                    <option value="video">Video (YouTube / dll)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="title" class="form-label">Judul Item <span style="color: var(--danger);">*</span></label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Masukkan judul foto atau video" required>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi / Keterangan</label>
                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Tuliskan keterangan singkat tentang foto atau video ini..."></textarea>
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label for="album" class="form-label">Nama Album</label>
                    <input type="text" name="album" id="album" class="form-control" placeholder="Contoh: PPDB 2026, Kelulusan, Umum" list="album-list">
                    <datalist id="album-list">
                        <?php foreach ($albums as $a): ?>
                            <?php if (!empty($a['album'])): ?>
                                <option value="<?= e($a['album']) ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </datalist>
                    <span class="form-text">Ketik album baru atau pilih dari album yang sudah ada.</span>
                </div>

                <div class="form-group">
                    <label for="sort_order" class="form-label">Urutan Tampilan</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" placeholder="Otomatis (terakhir)">
                </div>
            </div>

            <!-- Field khusus Tipe Video -->
            <div class="form-group" id="videoUrlGroup" style="display: none;">
                <label for="video_url" class="form-label">Link/URL Video <span style="color: var(--danger);">*</span></label>
                <input type="url" name="video_url" id="video_url" class="form-control" placeholder="Contoh: https://www.youtube.com/watch?v=xxxxxx">
                <span class="form-text">Masukkan link lengkap video YouTube.</span>
            </div>

            <!-- Field khusus Tipe Foto / Thumbnail Video -->
            <div class="form-group" id="fileGroup">
                <label for="file_path" class="form-label" id="fileLabel">Unggah File Foto <span style="color: var(--danger);">*</span></label>
                <input type="file" name="file_path" id="file_path" class="form-control-file" accept="image/*" onchange="previewImage(event)">
                <span class="form-text">Format: JPG, JPEG, PNG, WEBP. Maksimal 5MB.</span>
                
                <div style="margin-top: 1rem;">
                    <img id="imagePreview" src="#" alt="Preview Gambar" style="max-width: 250px; height: auto; border-radius: 8px; border: 1px solid var(--border); display: none;">
                </div>
            </div>

            <div class="form-group">
                <label for="is_active" class="form-label">Status Publikasi</label>
                <select name="is_active" id="is_active" class="form-select">
                    <option value="1" selected>Tampilkan di Website</option>
                    <option value="0">Sembunyikan</option>
                </select>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Simpan Item
                </button>
                <a href="<?= url('/admin/gallery') ?>" class="btn btn-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const typeSelect = document.getElementById('type');
    const videoUrlGroup = document.getElementById('videoUrlGroup');
    const videoUrlInput = document.getElementById('video_url');
    const fileGroup = document.getElementById('fileGroup');
    const fileInput = document.getElementById('file_path');
    const fileLabel = document.getElementById('fileLabel');

    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            if (this.value === 'video') {
                videoUrlGroup.style.display = 'block';
                videoUrlInput.required = true;
                
                // File upload menjadi opsional (sebagai cover/thumbnail video)
                fileLabel.innerHTML = 'Unggah Cover / Thumbnail Video (Opsional)';
                fileInput.required = false;
            } else {
                videoUrlGroup.style.display = 'none';
                videoUrlInput.required = false;
                videoUrlInput.value = '';
                
                // File upload menjadi wajib
                fileLabel.innerHTML = 'Unggah File Foto <span style="color: var(--danger);">*</span>';
                fileInput.required = true;
            }
        });

        // Trigger on load
        typeSelect.dispatchEvent(new Event('change'));
    }

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
