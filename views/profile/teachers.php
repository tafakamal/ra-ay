<?php
/**
 * Halaman Guru & Tenaga Pendidik
 * RA Attakal Yaqiin
 *
 * Expected variables:
 *   $teachers    - array of active teachers
 *   $breadcrumbs - array of breadcrumb items
 */
?>

<!-- Breadcrumbs -->
<?php require BASE_PATH . '/views/partials/breadcrumb.php'; ?>

<!-- Page Header -->
<section class="section py-12 bg-surface">
  <div class="container">
    <div data-animate="fade-in">
      <span class="text-primary font-semibold uppercase tracking-wider text-sm block mb-2">Profil Sekolah</span>
      <h1 class="text-4xl font-extrabold tracking-tight text-fg mb-4">Guru &amp; Tenaga Pendidik</h1>
      <p class="text-lg text-muted max-w-2xl">Ustadz dan Ustadzah yang penyayang, berkompeten, dan berdedikasi tinggi dalam membimbing tumbuh kembang ananda.</p>
    </div>
  </div>
</section>

<!-- Main Content Section -->
<section class="section">
  <div class="container">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      
      <!-- Content Area (Spans 3 cols on desktop) -->
      <div class="lg:col-span-3">
        <?php if (empty($teachers)): ?>
          <p class="text-center text-muted py-8 bg-card border rounded">Data guru belum tersedia.</p>
        <?php else: ?>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" data-stagger="80">
            <?php foreach ($teachers as $teacher): ?>
              <div class="card card-teacher" data-animate="slide-up">
                
                <!-- Avatar Image -->
                <div class="card__image">
                  <?php if (!empty($teacher['photo']) && file_exists(BASE_PATH . '/' . $teacher['photo'])): ?>
                    <img src="<?= upload_url($teacher['photo']) ?>" alt="<?= e($teacher['name']) ?>">
                  <?php else: ?>
                    <img src="https://placehold.co/300x300/FFF0E5/E8600A?text=<?= urlencode(explode(' ', $teacher['name'])[0]) ?>" alt="<?= e($teacher['name']) ?>">
                  <?php endif; ?>
                </div>

                <!-- Teacher Info -->
                <div class="card__body">
                  <span class="card__role"><?= e($teacher['position']) ?></span>
                  <h3 class="card__title" style="margin-top: 4px; margin-bottom: 2px; font-size: 1.15rem;"><?= e($teacher['name']) ?></h3>
                  
                  <?php if (!empty($teacher['nip'])): ?>
                    <p class="text-xs text-muted mb-3">NIP: <?= e($teacher['nip']) ?></p>
                  <?php else: ?>
                    <p class="text-xs text-muted mb-3">&nbsp;</p>
                  <?php endif; ?>

                  <p class="card__text" style="font-size: 0.825rem; line-height: 1.5; min-height: 60px;"><?= e(truncate($teacher['bio'] ?? '', 100)) ?></p>
                  
                  <div class="card__meta justify-center mt-4 pt-3 border-t">
                    <?php if (!empty($teacher['education'])): ?>
                      <span class="card__meta-item" title="Pendidikan Terakhir">
                        <i class="ti ti-school"></i> <?= e($teacher['education']) ?>
                      </span>
                    <?php endif; ?>
                  </div>
                </div>

              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar Area (Spans 1 col on desktop) -->
      <aside class="lg:col-span-1" data-animate="slide-left">
        <div class="bg-card border rounded p-6 sticky" style="top: calc(var(--navbar-height) + var(--space-4));">
          <h3 class="text-base font-bold text-fg border-b pb-3 mb-4">Navigasi Profil</h3>
          <ul class="flex flex-col gap-2">
            <li>
              <a href="/profil/tentang" class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium transition text-fg-muted hover:bg-primary-light hover:text-primary">
                <i class="ti ti-info-circle" style="font-size: 18px;"></i>
                Tentang Kami
              </a>
            </li>
            <li>
              <a href="/profil/visi-misi" class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium transition text-fg-muted hover:bg-primary-light hover:text-primary">
                <i class="ti ti-target" style="font-size: 18px;"></i>
                Visi &amp; Misi
              </a>
            </li>
            <li>
              <a href="/profil/guru" class="flex items-center gap-2 px-3 py-2 rounded text-sm font-semibold transition bg-primary text-white">
                <i class="ti ti-users" style="font-size: 18px;"></i>
                Guru &amp; Staff
              </a>
            </li>
            <li>
              <a href="/profil/fasilitas" class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium transition text-fg-muted hover:bg-primary-light hover:text-primary">
                <i class="ti ti-building" style="font-size: 18px;"></i>
                Fasilitas
              </a>
            </li>
          </ul>

          <div class="mt-8 pt-6 border-t">
            <h4 class="text-xs font-bold uppercase tracking-wider text-muted mb-3">Butuh Informasi?</h4>
            <a href="/kontak" class="btn btn-outline btn-sm w-full">Hubungi Kami <i class="ti ti-phone"></i></a>
          </div>
        </div>
      </aside>

    </div>
  </div>
</section>
