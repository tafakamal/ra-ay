<?php
/**
 * Halaman Galeri Foto
 * RA Attakal Yaqiin
 *
 * Expected variables:
 *   $items          - array of gallery items
 *   $albums         - array of unique albums with counts
 *   $selectedAlbum  - string|null, currently selected album filter
 */
?>

<!-- Breadcrumbs -->
<?php require BASE_PATH . '/views/partials/breadcrumb.php'; ?>

<!-- Page Header -->
<section class="section py-12 bg-surface">
  <div class="container">
    <div data-animate="fade-in">
      <span class="text-primary font-semibold uppercase tracking-wider text-sm block mb-2">Dokumentasi</span>
      <h1 class="text-4xl font-extrabold tracking-tight text-fg mb-4">Galeri Kegiatan Sekolah</h1>
      <p class="text-lg text-muted max-w-2xl">Dokumentasi momen-momen indah keceriaan, kreativitas, dan kekhusyukan belajar putra-putri kami.</p>
    </div>
  </div>
</section>

<!-- Filter & Grid Section -->
<section class="section">
  <div class="container">
    
    <!-- Album Filters -->
    <?php if (!empty($albums)): ?>
      <div class="flex flex-wrap gap-2 justify-center mb-10" data-animate="fade-in">
        <!-- All Album Button -->
        <a href="/galeri" 
           class="btn btn-sm <?= $selectedAlbum === null ? 'btn-primary' : 'btn-outline' ?>" 
           style="border-radius: var(--radius-full);">
          Semua Kegiatan
        </a>

        <!-- Custom Album Buttons -->
        <?php foreach ($albums as $album): ?>
          <a href="/galeri?album=<?= urlencode($album['album']) ?>" 
             class="btn btn-sm <?= $selectedAlbum === $album['album'] ? 'btn-primary' : 'btn-outline' ?>" 
             style="border-radius: var(--radius-full);">
            <?= e($album['album']) ?> (<?= $album['item_count'] ?>)
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Gallery Grid -->
    <?php if (empty($items)): ?>
      <div class="text-center py-16 bg-card border rounded" data-animate="slide-up">
        <i class="ti ti-photo-off text-muted" style="font-size: 64px; display: block; margin-bottom: var(--space-4);"></i>
        <h3 class="text-lg font-bold text-fg">Foto Belum Tersedia</h3>
        <p class="text-fg-muted mt-2">Maaf, saat ini belum ada foto kegiatan yang diunggah ke dalam album ini.</p>
        <a href="/galeri" class="btn btn-primary btn-sm mt-6">Kembali ke Semua Foto</a>
      </div>
    <?php else: ?>
      <div class="gallery-grid" data-stagger="50">
        <?php foreach ($items as $item): ?>
          <div class="gallery-item" 
               data-animate="scale-in"
               data-lightbox="main-gallery"
               data-lightbox-src="<?= upload_url($item['file_path']) ?>"
               data-lightbox-alt="<?= e($item['title']) . ($item['description'] ? ' - ' . e($item['description']) : '') ?>">
            
            <!-- Thumbnail Image -->
            <?php if (!empty($item['thumbnail_path']) && file_exists(BASE_PATH . '/' . $item['thumbnail_path'])): ?>
              <img src="<?= upload_url($item['thumbnail_path']) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
            <?php elseif (!empty($item['file_path']) && file_exists(BASE_PATH . '/' . $item['file_path'])): ?>
              <img src="<?= upload_url($item['file_path']) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
            <?php else: ?>
              <img src="https://placehold.co/400x400/FFF0E5/E8600A?text=<?= urlencode($item['title']) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
            <?php endif; ?>

            <!-- Overlay on Hover -->
            <div class="gallery-item__overlay">
              <div class="gallery-item__overlay-icon">
                <i class="ti ti-zoom-in" style="font-size: 24px;"></i>
              </div>
              <div class="gallery-item__caption"><?= e($item['title']) ?></div>
            </div>
            
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>
