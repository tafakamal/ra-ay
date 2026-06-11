<?php
/**
 * Halaman Detail Berita
 * RA Attakal Yaqiin
 *
 * Expected variables:
 *   $article     - array of the news article detail
 *   $categories  - array of categories with count
 *   $latestNews  - array of latest news for sidebar
 *   $relatedNews - array of up to 3 related news articles
 */

// Generate absolute URL for sharing
$shareUrl = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? 'raattakalyaqiin.sch.id') . '/berita/' . $article['slug'];
$shareText = urlencode('Baca berita menarik ini: ' . $article['title']);
?>

<!-- Breadcrumbs -->
<?php require BASE_PATH . '/views/partials/breadcrumb.php'; ?>

<!-- Main Detail Section -->
<section class="section">
  <div class="container">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      
      <!-- Main Content Area (Spans 3 cols on desktop) -->
      <div class="lg:col-span-3" data-animate="slide-up">
        <article class="bg-card border rounded p-6 md:p-10 shadow-sm">
          
          <!-- Category Badge -->
          <span class="card__tag" style="background-color: <?= e($article['category_color'] ?? '#FFF0E5') ?>20; color: <?= e($article['category_color'] ?? '#E8600A') ?>; margin-bottom: var(--space-4);">
            <?= e($article['category_name'] ?? 'Umum') ?>
          </span>

          <!-- Title -->
          <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-fg mb-4" style="line-height:1.2;"><?= e($article['title']) ?></h1>

          <!-- Meta Information -->
          <div class="flex flex-wrap items-center gap-4 text-xs text-muted mb-8 pb-6 border-b">
            <span class="flex items-center gap-1">
              <i class="ti ti-calendar-event" style="font-size:14px;"></i> <?= format_date($article['published_at'], 'long') ?>
            </span>
            <span class="flex items-center gap-1">
              <i class="ti ti-user" style="font-size:14px;"></i> Oleh: <?= e($article['author_name'] ?? 'Administrator') ?>
            </span>
            <span class="flex items-center gap-1">
              <i class="ti ti-eye" style="font-size:14px;"></i> Dibaca <?= number_format((int)$article['views']) ?> kali
            </span>
          </div>

          <!-- Featured Image -->
          <div class="mb-8 rounded overflow-hidden shadow-sm" style="max-height: 480px; width: 100%;">
            <?php if (!empty($article['featured_image']) && file_exists(BASE_PATH . '/' . $article['featured_image'])): ?>
              <img src="<?= upload_url($article['featured_image']) ?>" alt="<?= e($article['title']) ?>" class="w-full" style="object-fit: cover;">
            <?php else: ?>
              <img src="https://placehold.co/800x450/FFF0E5/E8600A?text=<?= urlencode($article['title']) ?>" alt="<?= e($article['title']) ?>" class="w-full" style="object-fit: cover;">
            <?php endif; ?>
          </div>

          <!-- Article Content -->
          <div class="rich-content mb-10" style="line-height: var(--leading-relaxed); color: var(--fg-muted);">
            <?= $article['content'] ?>
          </div>

          <!-- Social Share Bar -->
          <div class="flex flex-wrap items-center justify-between gap-4 pt-6 border-t">
            <span class="text-sm font-semibold text-fg">Bagikan berita ini:</span>
            <div class="flex gap-2">
              <!-- WhatsApp -->
              <a href="https://api.whatsapp.com/send?text=<?= $shareText ?>%20<?= urlencode($shareUrl) ?>" 
                 target="_blank" rel="noopener" 
                 class="btn btn-sm btn-whatsapp" 
                 style="padding: var(--space-2) var(--space-4); font-size: 12px; border-radius:var(--radius-xs);">
                <i class="ti ti-brand-whatsapp"></i> WhatsApp
              </a>
              <!-- Facebook -->
              <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($shareUrl) ?>" 
                 target="_blank" rel="noopener" 
                 class="btn btn-sm" 
                 style="background-color: #1877F2; color: #fff; padding: var(--space-2) var(--space-4); font-size: 12px; border-radius:var(--radius-xs);">
                <i class="ti ti-brand-facebook"></i> Facebook
              </a>
              <!-- Twitter / X -->
              <a href="https://twitter.com/intent/tweet?url=<?= urlencode($shareUrl) ?>&text=<?= $shareText ?>" 
                 target="_blank" rel="noopener" 
                 class="btn btn-sm" 
                 style="background-color: #0F1419; color: #fff; padding: var(--space-2) var(--space-4); font-size: 12px; border-radius:var(--radius-xs);">
                <i class="ti ti-brand-x"></i> X / Twitter
              </a>
            </div>
          </div>

        </article>

        <!-- Related News Section -->
        <?php if (!empty($relatedNews)): ?>
          <div class="mt-12" data-animate="slide-up">
            <h3 class="text-xl font-bold text-fg mb-6">Berita Terkait</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <?php foreach ($relatedNews as $related): ?>
                <div class="card card-news" style="font-size: 0.9rem;">
                  <div class="card__image" style="aspect-ratio: 16/10;">
                    <a href="/berita/<?= e($related['slug']) ?>">
                      <?php if (!empty($related['featured_image']) && file_exists(BASE_PATH . '/' . $related['featured_image'])): ?>
                        <img src="<?= upload_url($related['featured_image']) ?>" alt="" style="width:100%; height:100%; object-fit:cover;">
                      <?php else: ?>
                        <img src="https://placehold.co/400x250/FFF0E5/E8600A?text=<?= urlencode($related['title']) ?>" alt="" style="width:100%; height:100%; object-fit:cover;">
                      <?php endif; ?>
                    </a>
                  </div>
                  <div class="card__body" style="padding: var(--space-4);">
                    <div class="card__date" style="font-size: 10px; margin-bottom: 4px;">
                      <i class="ti ti-calendar-event"></i> <?= format_date($related['published_at'], 'short') ?>
                    </div>
                    <h4 class="font-bold text-fg line-clamp-2" style="font-size: 0.95rem; line-height: 1.35; margin-bottom: 6px;">
                      <a href="/berita/<?= e($related['slug']) ?>" class="hover:text-primary transition"><?= e($related['title']) ?></a>
                    </h4>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
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
                  <a href="/berita/kategori/<?= e($cat['slug']) ?>" 
                     class="flex justify-between items-center px-3 py-2 rounded text-sm font-medium transition <?= $cat['slug'] === ($article['category_slug'] ?? '') ? 'bg-primary text-white hover:bg-primary' : 'hover:bg-primary-light hover:text-primary' ?>">
                    <span class="flex items-center gap-2">
                      <span style="width: 8px; height: 8px; border-radius: 50%; background-color: <?= $cat['slug'] === ($article['category_slug'] ?? '') ? '#FFF' : e($cat['color'] ?? '#E8600A') ?>;"></span>
                      <?= e($cat['name']) ?>
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full <?= $cat['slug'] === ($article['category_slug'] ?? '') ? 'bg-white text-primary' : 'text-muted bg-surface' ?>"><?= $cat['news_count'] ?></span>
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

<!-- Custom Styling for Database Rich Text Content -->
<style>
.rich-content h2, .rich-content h3, .rich-content h4 {
  color: var(--fg);
  font-weight: 700;
  margin-top: 1.75rem;
  margin-bottom: 0.75rem;
  letter-spacing: -0.01em;
}
.rich-content h2 { font-size: var(--text-2xl); border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; }
.rich-content h3 { font-size: var(--text-xl); }
.rich-content h4 { font-size: var(--text-lg); }
.rich-content p { margin-bottom: 1.25rem; }
.rich-content ul, .rich-content ol {
  margin-bottom: 1.5rem;
  padding-left: 1.5rem;
}
.rich-content ul { list-style-type: disc; }
.rich-content ol { list-style-type: decimal; }
.rich-content li { margin-bottom: 0.5rem; }
.rich-content strong { color: var(--fg); }
</style>
