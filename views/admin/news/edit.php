<?php
/**
 * View Edit Berita
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<!-- Include Quill.js WYSIWYG CDN Styles -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="panel-card">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-edit"></i> Edit Berita: <?= e($news['title']) ?></h2>
        <a href="<?= url('/admin/news') ?>" class="btn btn-outline">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="panel-body">
        <form action="<?= url('/admin/news/update/' . $news['id']) ?>" method="POST" enctype="multipart/form-data" id="newsForm">
            <?= csrf_field() ?>

            <div class="form-row form-row-2">
                <!-- Left Column (Core News Data) -->
                <div>
                    <div class="form-group">
                        <label for="title" class="form-label">Judul Berita <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Masukkan judul berita" value="<?= e($news['title']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="excerpt" class="form-label">Ringkasan Singkat (Excerpt)</label>
                        <textarea name="excerpt" id="excerpt" class="form-control" rows="3" placeholder="Ringkasan berita"><?= e($news['excerpt']) ?></textarea>
                        <span class="form-text">Maksimal 150-200 karakter. Ditampilkan pada daftar berita/artikel.</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Isi Berita / Konten <span style="color: var(--danger);">*</span></label>
                        <input type="hidden" name="content" id="content-input">
                        <div class="editor-wrapper">
                            <!-- Preload content directly into Quill div -->
                            <div id="editor" style="min-height: 300px;"><?= $news['content'] ?></div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Metadata, Status, Image) -->
                <div>
                    <div class="form-group">
                        <label for="category_id" class="form-label">Kategori Berita</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">-- Tanpa Kategori (Umum) --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= (string)$news['category_id'] === (string)$cat['id'] ? 'selected' : '' ?>>
                                    <?= e($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status Penerbitan</label>
                        <select name="status" id="status" class="form-select">
                            <option value="draft" <?= $news['status'] === 'draft' ? 'selected' : '' ?>>Draft (Belum Diterbitkan)</option>
                            <option value="published" <?= $news['status'] === 'published' ? 'selected' : '' ?>>Published (Terbitkan)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pengaturan Tambahan</label>
                        <div class="form-check" style="margin-bottom: 0.5rem;">
                            <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1" <?= $news['is_featured'] ? 'checked' : '' ?>>
                            <label for="is_featured" class="form-check-label" style="font-weight: 500; cursor: pointer;">Jadikan Berita Utama (Featured)</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="featured_image" class="form-label">Gambar Utama (Featured Image)</label>
                        <input type="file" name="featured_image" id="featured_image" class="form-control-file" accept="image/*" onchange="previewImage(event)">
                        <span class="form-text">Format: JPG, JPEG, PNG, WEBP. Maksimal 5MB. Kosongkan jika tidak ingin mengubah gambar.</span>
                        
                        <div style="margin-top: 1rem;">
                            <!-- Tampilkan gambar lama jika ada -->
                            <?php if (!empty($news['featured_image'])): ?>
                                <div id="oldImageContainer" style="margin-bottom: 0.5rem;">
                                    <span style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Gambar Saat Ini:</span>
                                    <img src="<?= upload_url($news['featured_image']) ?>" alt="Featured Image" style="max-width: 200px; height: auto; border-radius: 6px; border: 1px solid var(--border);">
                                </div>
                            <?php endif; ?>
                            
                            <!-- Preview gambar baru -->
                            <div id="newPreviewContainer" style="display: none;">
                                <span style="font-size: 0.8rem; color: var(--primary); display: block; margin-bottom: 0.25rem;">Gambar Baru (Preview):</span>
                                <img id="imagePreview" src="#" alt="Preview Gambar" style="max-width: 200px; height: auto; border-radius: 6px; border: 1px solid var(--border);">
                            </div>
                        </div>
                    </div>

                    <!-- SEO Section Accordion -->
                    <div style="border: 1px solid var(--border); border-radius: 8px; margin-top: 2rem;">
                        <div style="background-color: var(--light); padding: 0.75rem 1rem; border-bottom: 1px solid var(--border); font-weight: 600; font-size: 0.9rem; color: var(--secondary); display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ti ti-search"></i> Pengaturan SEO (Opsional)
                        </div>
                        <div style="padding: 1rem;">
                            <div class="form-group">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" id="meta_title" class="form-control" placeholder="Judul SEO" value="<?= e($news['meta_title']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" class="form-control" rows="2" placeholder="Deskripsi SEO"><?= e($news['meta_description']) ?></textarea>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" placeholder="keyword1, keyword2, dst." value="<?= e($news['meta_keywords']) ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Simpan Perubahan
                </button>
                <a href="<?= url('/admin/news') ?>" class="btn btn-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Include Quill.js CDN Library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    // Initialize Quill Editor
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Tulis isi konten berita/artikel secara lengkap...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                ['link', 'blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['clean']
            ]
        }
    });

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

    // Submit form handler
    var form = document.getElementById('newsForm');
    form.onsubmit = function(e) {
        // Salin data HTML dari Quill ke hidden input
        var contentInput = document.getElementById('content-input');
        var htmlContent = quill.root.innerHTML;
        
        // Cek jika kosong
        var textContent = quill.getText().trim();
        if (textContent.length === 0) {
            alert('Konten berita tidak boleh kosong.');
            e.preventDefault();
            return false;
        }

        contentInput.value = htmlContent;
        return true;
    };
</script>
