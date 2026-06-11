<?php
/**
 * Halaman Daftar Berita / Hasil Pencarian
 * RA Attakal Yaqiin
 *
 * Expected variables:
 *   $newsData    - array containing 'data' (news list), 'total', 'pages', 'page'
 *   $categories  - array of categories with news count
 *   $latestNews  - array of latest 4 news articles for sidebar
 *   $keyword     - string, search term (only in search action)
 */

$keyword = $keyword ?? '';
$articles = $newsData['data'] ?? [];
$totalPages = $newsData['pages'] ?? 1;
$currentPageNum = $newsData['page'] ?? 1;

// Helper to generate page URL preserving search query
$pageUrl = function(int $pageNum) use ($keyword): string {
    if ($keyword !== '') {
        return '/berita/cari?q=' . urlencode($keyword) . '&page=' . $pageNum;
    }
    return '/berita?page=' . $pageNum;
};
?>

<!-- Breadcrumbs -->
<?php require BASE_PATH . '/views/partials/breadcrumb.php'; ?>

<!-- Page Header -->
<section class="section py-12 bg-surface">
  <div class="container">
    <div data-animate="fade-in">
      <span class="text-primary font-semibold uppercase tracking-wider text-sm block mb-2">Berita &amp; Informasi</span>
      <?php if ($keyword !== ''): ?>
        <h1 class="text-4xl font-extrabold tracking-tight text-fg mb-4">Pencarian: <span>"<?= e($keyword) ?>"</span></h1>
        <p class="text-lg text-muted max-w-2xl">Ditemukan <?= $newsData['total'] ?> berita dengan kata kunci tersebut.</p>
      <?php else: ?>
        <h1 class="text-4xl font-extrabold tracking-tight text-fg mb-4">Kabar &amp; Artikel Sekolah</h1>
        <p class="text-lg text-muted max-w-2xl">Ikuti kegiatan belajar mengajar, pengumuman resmi, prestasi siswa, serta artikel parenting bermanfaat.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Main Content Section -->
<section class="section">
  <div class="container">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      
      <!-- Main Content Area (Spans 3 cols on desktop) -->
      <div class="lg:col-span-3">
        <?php if (empty($articles)): ?>
          <div class="text-center py-16 bg-card border rounded" data-animate="slide-up">
            <i class="ti ti-news-off text-muted" style="font-size: 64px; display: block; margin-bottom: var(--space-4);"></i>
            <h3 class="text-lg font-bold text-fg">Berita Tidak Ditemukan</h3>
            <p class="text-fg-muted mt-2">Maaf, kami tidak menemukan berita yang cocok dengan kriteria Anda.</p>
            <a href="/berita" class="btn btn-primary btn-sm mt-6">Kembali ke Semua Berita</a>
          </div>
        <?php else: ?>
          
          <!-- News Grid -->
          <div class="news-grid" data-stagger="100">
            <?php foreach ($articles as $news): ?>
              <article class="card card-news" data-animate="slide-up">
                
                <!-- Image -->
                <div class="card__image">
                  <a href="/berita/<?= e($news['slug']) ?>">
                    <?php if (!empty($news['featured_image']) && file_exists(BASE_PATH . '/' . $news['featured_image'])): ?>
                      <img src="<?= upload_url($news['featured_image']) ?>" alt="<?= e($news['title']) ?>">
                    <?php else: ?>
                      <img src="https://placehold.co/600x400/FFF0E5/E8600A?text=<?= urlencode($news['title']) ?>" alt="<?= e($news['title']) ?>">
                    <?php endif; ?>
                  </a>
                  <div class="card__image-overlay"></div>
                </div>

                <!-- Body -->
                <div class="card__body">
                  <!-- Category Badge -->
                  <span class="card__tag" style="background-color: <?= e($news['category_color'] ?? '#FFF0E5') ?>20; color: <?= e($news['category_color'] ?? '#E8600A') ?>;">
                    <?= e($news['category_name'] ?? 'Umum') ?>
                  </span>

                  <!-- Date & Author -->
                  <div class="card__date mb-2">
                    <i class="ti ti-calendar-event"></i> <?= format_date($news['published_at'], 'short') ?>
                    <span style="margin: 0 6px;">•</span>
                    <i class="ti ti-user"></i> <?= e($news['author_name'] ?? 'Admin') ?>
                  </div>

                  <!-- Title -->
                  <h3 class="card__title">
                    <a href="/berita/<?= e($news['slug']) ?>"><?= e($news['title']) ?></a>
                  </h3>

                  <!-- Excerpt -->
                  <p class="card__excerpt"><?= e(truncate($news['excerpt'] ?? $news['content'], 130)) ?></p>
                  
                  <!-- Action -->
                  <a href="/berita/<?= e($news['slug']) ?>" class="card__read-more">
                    Baca Selengkapnya <i class="ti ti-arrow-right"></i>
                  </a>
                </div>

              </article>
            <?php endforeach; ?>
          </div>

          <!-- Pagination -->
          <?php if ($totalPages > 1): ?>
            <nav class="pagination" aria-label="Navigasi Halaman Berita" data-animate="fade-in">
              <!-- Previous Button -->
              <a href="<?= $pageUrl($currentPageNum - 1) ?>" 
                 class="pagination__item <?= $currentPageNum <= 1 ? 'disabled' : '' ?>" 
                 aria-label="Halaman sebelumnya">
                <i class="ti ti-chevron-left"></i>
              </a>

              <!-- Page Numbers -->
              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="<?= $pageUrl($i) ?>" 
                   class="pagination__item <?= $i === $currentPageNum ? 'active' : '' ?>">
                  <?= $i ?>
                </a>
              <?php endfor; ?>

              <!-- Next Button -->
              <a href="<?= $pageUrl($currentPageNum + 1) ?>" 
                 class="pagination__item <?= $currentPageNum >= $totalPages ? 'disabled' : '' ?>" 
                 aria-label="Halaman berikutnya">
                <i class="ti ti-chevron-right"></i>
              </a>
            </nav>
          <?php endif; ?>

        <?php endif; ?>
      </div>

      <!-- Sidebar Area (Spans 1 col on desktop) -->
      <aside class="lg:col-span-1" data-animate="slide-left">
        <div class="flex flex-col gap-8">
          
          <!-- Sidebar: Search -->
          <div class="bg-card border rounded p-6 shadow-sm">
            <h3 class="text-sm font-bold text-fg uppercase tracking-wider mb-4">Cari Berita</h3>
            <form action="/berita/cari" method="GET">
              <div class="relative">
                <input 
                  type="text" 
                  name="q" 
                  value="<?= e($keyword) ?>" 
                  placeholder="Masukkan kata kunci..." 
                  class="form-control" 
                  style="padding-right: 40px;"
                  required
                >
                <button 
                  type="submit" 
                  class="absolute text-muted hover:text-primary transition" 
                  style="top: 50%; right: 12px; transform: translateY(-50%); background:none; border:none; padding:0; cursor:pointer;"
                  aria-label="Cari"
                >
                  <i class="ti ti-search" style="font-size: 20px;"></i>
                </button>
              </div>
            </form>
          </div>

          <!-- Sidebar: Categories -->
          <div class="bg-card border rounded p-6 shadow-sm">
            <h3 class="text-sm font-bold text-fg uppercase tracking-wider mb-4">Kategori</h3>
            <ul class="flex flex-col gap-2">
              <?php foreach ($categories as $cat): ?>
                <li>
                  <a href="/berita/kategori/<?= e($cat['slug']) ?>" class="flex justify-between items-center px-3 py-2 rounded text-sm font-medium transition hover:bg-primary-light hover:text-primary">
                    <span class="flex items-center gap-2">
                      <span style="width: 8px; height: 8px; border-radius: 50%; background-color: <?= e($cat['color'] ?? '#E8600A') ?>;"></span>
                      <?= e($cat['name']) ?>
                    </span>
                    <span class="text-xs text-muted bg-surface px-2 py-0.5 rounded-full"><?= $cat['news_count'] ?></span>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>

          <!-- Sidebar: Recent Posts -->
          <div class="bg-card border rounded p-6 shadow-sm">
            <h3 class="text-sm font-bold text-fg uppercase tracking-wider mb-4">Berita Terbaru</h3>
            <div class="flex flex-col gap-4">
              <?php foreach ($latestNews as $recent): ?>
                <div class="flex gap-3">
                  <!-- Thumbnail -->
                  <div style="width: 70px; height: 50px; flex-shrink: 0; border-radius: 4px; overflow:hidden;">
                    <?php if (!empty($recent['featured_image']) && file_exists(BASE_PATH . '/' . $recent['featured_image'])): ?>
                      <img src="<?= upload_url($recent['featured_image']) ?>" alt="" class="w-full h-full" style="object-fit: cover;">
                    <?php else: ?>
                      <img src="https://placehold.co/100x100/FFF0E5/E8600A?text=<?= urlencode(explode(' ', $recent['title'])[0]) ?>" alt="" class="w-full h-full" style="object-fit: cover;">
                    <?php endif; ?>
                  </div>
                  <!-- Details -->
                  <div style="min-width: 0;">
                    <a href="/berita/<?= e($recent['slug']) ?>" class="block font-semibold text-xs text-fg hover:text-primary transition line-clamp-2" style="line-height:1.35;">
                      <?= e($recent['title']) ?>
                    </a>
                    <span class="text-[10px] text-muted block mt-1">
                      <i class="ti ti-calendar-event"></i> <?= format_date($recent['published_at'], 'short') ?>
                    </span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

        </div>
      </aside>

    </div>
  </div>
</section>
