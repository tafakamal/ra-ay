<?php
/**
 * Halaman Program Unggulan
 * RA Attakal Yaqiin
 *
 * Expected variables:
 *   $page        - array containing page details (title, content, etc.)
 *   $breadcrumbs - array of breadcrumb items
 */
?>

<!-- Breadcrumbs -->
<?php require BASE_PATH . '/views/partials/breadcrumb.php'; ?>

<!-- Page Header -->
<section class="section py-12 bg-surface">
  <div class="container">
    <div data-animate="fade-in">
      <span class="text-primary font-semibold uppercase tracking-wider text-sm block mb-2">Akademik Sekolah</span>
      <h1 class="text-4xl font-extrabold tracking-tight text-fg mb-4"><?= e($page['title']) ?></h1>
      <p class="text-lg text-muted max-w-2xl">Daftar kegiatan, materi pembelajaran khusus, dan program stimulasi holistik yang menjadi kekhasan RA Attakal Yaqiin.</p>
    </div>
  </div>
</section>

<!-- Main Content Section -->
<section class="section">
  <div class="container">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      
      <!-- Content Area (Spans 3 cols on desktop) -->
      <div class="lg:grid-cols-3 lg:col-span-3" data-animate="slide-up">
        <div class="bg-card border rounded p-6 md:p-10 shadow-sm">
          
          <!-- Program Banner Image -->
          <div class="mb-8 rounded overflow-hidden shadow-sm" style="aspect-ratio: 21/9; max-height: 350px;">
            <img src="https://placehold.co/800x400/FFF0E5/E8600A?text=Keceriaan+Anak+dalam+Program+Unggulan+Sekolah" alt="Program Unggulan RA Attakal Yaqiin" class="w-full h-full" style="object-fit: cover;">
          </div>

          <!-- Rich Text Content -->
          <div class="rich-content" style="line-height: var(--leading-relaxed); color: var(--fg-muted);">
            <?= $page['content'] ?>
          </div>

        </div>
      </div>

      <!-- Sidebar Area (Spans 1 col on desktop) -->
      <aside class="lg:col-span-1" data-animate="slide-left">
        <div class="bg-card border rounded p-6 sticky" style="top: calc(var(--navbar-height) + var(--space-4));">
          <h3 class="text-base font-bold text-fg border-b pb-3 mb-4">Navigasi Akademik</h3>
          <ul class="flex flex-col gap-2">
            <li>
              <a href="/akademik/kurikulum" class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium transition text-fg-muted hover:bg-primary-light hover:text-primary">
                <i class="ti ti-book" style="font-size: 18px;"></i>
                Kurikulum
              </a>
            </li>
            <li>
              <a href="/akademik/program-unggulan" class="flex items-center gap-2 px-3 py-2 rounded text-sm font-semibold transition bg-primary text-white">
                <i class="ti ti-star" style="font-size: 18px;"></i>
                Program Unggulan
              </a>
            </li>
          </ul>

          <div class="mt-8 pt-6 border-t">
            <h4 class="text-xs font-bold uppercase tracking-wider text-muted mb-3">Ingin Mendaftar?</h4>
            <a href="/ppdb" class="btn btn-secondary btn-sm w-full">Daftar PPDB <i class="ti ti-user-plus"></i></a>
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
