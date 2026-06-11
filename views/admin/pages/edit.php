<?php
/**
 * View Edit Halaman Statis
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<!-- Include Quill.js WYSIWYG CDN Styles -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="panel-card">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-edit"></i> Edit Halaman: <?= e($page['title']) ?></h2>
        <a href="<?= url('/admin/pages') ?>" class="btn btn-outline">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="panel-body">
        <form action="<?= url('/admin/pages/update/' . $page['id']) ?>" method="POST" id="pageForm">
            <?= csrf_field() ?>

            <div class="form-row form-row-2">
                <!-- Left Column: Core Data -->
                <div>
                    <div class="form-group">
                        <label for="title" class="form-label">Judul Halaman <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Masukkan judul halaman" value="<?= e($page['title']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konten Halaman <span style="color: var(--danger);">*</span></label>
                        <input type="hidden" name="content" id="content-input">
                        <div class="editor-wrapper">
                            <div id="editor" style="min-height: 350px;"><?= $page['content'] ?></div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Settings & SEO -->
                <div>
                    <div class="form-group">
                        <label for="slug" class="form-label">URL Slug</label>
                        <input type="text" id="slug" class="form-control" value="/<?= e($page['slug']) ?>" readonly style="background-color: var(--light); color: var(--text-muted);">
                        <span class="form-text">Slug URL ditentukan otomatis berdasarkan judul saat disimpan.</span>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status Halaman</label>
                        <select name="status" id="status" class="form-select">
                            <option value="published" <?= $page['status'] === 'published' ? 'selected' : '' ?>>Published (Aktif & Muncul di Menu)</option>
                            <option value="draft" <?= $page['status'] === 'draft' ? 'selected' : '' ?>>Draft (Sembunyikan dari Publik)</option>
                        </select>
                    </div>

                    <!-- SEO Accordion Section -->
                    <div style="border: 1px solid var(--border); border-radius: 8px; margin-top: 2rem;">
                        <div style="background-color: var(--light); padding: 0.75rem 1rem; border-bottom: 1px solid var(--border); font-weight: 600; font-size: 0.9rem; color: var(--secondary); display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ti ti-search"></i> Optimasi SEO (Mesin Pencari)
                        </div>
                        <div style="padding: 1rem;">
                            <div class="form-group">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" id="meta_title" class="form-control" placeholder="Judul Halaman di Google" value="<?= e($page['meta_title']) ?>">
                                <span class="form-text">Jika kosong, akan disamakan dengan judul halaman.</span>
                            </div>
                            
                            <div class="form-group">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" class="form-control" rows="3" placeholder="Deskripsi ringkas yang muncul di hasil pencarian Google"><?= e($page['meta_description']) ?></textarea>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" placeholder="kata kunci 1, kata kunci 2, dst." value="<?= e($page['meta_keywords']) ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Simpan Halaman
                </button>
                <a href="<?= url('/admin/pages') ?>" class="btn btn-outline">
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
        placeholder: 'Tulis isi konten halaman secara lengkap...',
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

    // Submit form handler
    var form = document.getElementById('pageForm');
    form.onsubmit = function(e) {
        // Salin data HTML dari Quill ke hidden input
        var contentInput = document.getElementById('content-input');
        var htmlContent = quill.root.innerHTML;
        
        // Cek jika kosong
        var textContent = quill.getText().trim();
        if (textContent.length === 0) {
            alert('Konten halaman tidak boleh kosong.');
            e.preventDefault();
            return false;
        }

        contentInput.value = htmlContent;
        return true;
    };
</script>
