<?php
/**
 * Halaman Utama (Homepage)
 * RA Attakal Yaqiin
 *
 * Expected variables:
 *   $latestNews    – array of 3 latest news articles
 *   $teachers      – array of active teachers
 *   $latestGallery – array of 6 latest gallery items
 *   $settings      – array of SEO/site settings
 */
?>

<!-- ======================== HERO SECTION ======================== -->
<section class="hero">
  <div class="hero__bg">
    <img src="https://placehold.co/1920x1080/e8600a/ffffff?text=RA+Attakal+Yaqiin" alt="RA Attakal Yaqiin Background" data-parallax="0.2">
  </div>
  <div class="hero__overlay"></div>
  
  <!-- Floating Ornaments -->
  <div class="hero__ornament hero__ornament--1">
    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="1"><path d="M12 3a9 9 0 1 0 9 9h-9Z"/><path d="M12 12V3a9 9 0 0 1 9 9Z"/></svg>
  </div>
  <div class="hero__ornament hero__ornament--2">
    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="1"><circle cx="12" cy="12" r="10"/><path d="m12 8-4 4 4 4 4-4z"/></svg>
  </div>
  <div class="hero__ornament hero__ornament--3">
    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="1"><path d="M12 2L2 7l10 5 10-5-10-5Z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
  </div>

  <!-- Floating Particles -->
  <div class="hero__particles">
    <span class="hero__particle"></span>
    <span class="hero__particle"></span>
    <span class="hero__particle"></span>
    <span class="hero__particle"></span>
    <span class="hero__particle"></span>
    <span class="hero__particle"></span>
    <span class="hero__particle"></span>
    <span class="hero__particle"></span>
  </div>

  <div class="hero__content">
    <div class="hero__badge">
      <i class="ti ti-gift" style="font-size:16px;"></i> Selamat Datang di RA Attakal Yaqiin
    </div>
    <h1 class="hero__title">Menanamkan <span>Akhlak Mulia</span> Sejak Dini</h1>
    <p class="hero__subtitle">
      Membimbing putra-putri Anda menjadi generasi yang sholeh, cerdas, kreatif, dan mandiri berlandaskan nilai-nilai Al-Qur'an dan Sunnah.
    </p>
    <div class="hero__actions">
      <a href="/profil/tentang" class="btn btn-primary btn-lg">Tentang Kami</a>
      <a href="/ppdb" class="btn btn-white btn-lg">PPDB 2026/2027</a>
    </div>
  </div>

  <div class="hero__scroll">
    <span>Scroll</span>
    <span class="hero__scroll-line"></span>
  </div>
</section>

<!-- ======================== SAMBUTAN KEPALA SEKOLAH ======================== -->
<section class="section">
  <div class="container">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
      <div data-animate="slide-right">
        <div class="relative" style="display:inline-block; width:100%;">
          <!-- Decorative frame for photo -->
          <div class="absolute inset-0 bg-primary rounded" style="transform: rotate(3deg); z-index: 1; opacity:0.1; width:100%; height:100%;"></div>
          <img src="https://placehold.co/400x500/eaeaea/E8600A?text=Hj.+Siti+Aminah,+S.Pd.I" 
               alt="Kepala Sekolah RA Attakal Yaqiin" 
               class="rounded shadow-lg relative" 
               style="z-index: 2; width: 100%; max-width: 400px; margin: 0 auto; object-fit: cover;">
        </div>
      </div>
      <div data-animate="slide-up">
        <span class="section-label">Sambutan Kepala Sekolah</span>
        <h2 class="section-title mb-6">Mendidik dengan Hati, Membentuk <span>Generasi Qurani</span></h2>
        <p class="text-base text-fg mb-4">
          <em>Assalamu'alaikum Warahmatullahi Wabarakatuh,</em>
        </p>
        <p class="text-fg-muted mb-4">
          Segala puji bagi Allah SWT yang telah melimpahkan rahmat dan hidayah-Nya kepada kita semua. Shalawat serta salam senantiasa tercurah kepada junjungan kita Nabi Muhammad SAW.
        </p>
        <p class="text-fg-muted mb-4">
          Selamat datang di website resmi RA Attakal Yaqiin. Kami merasa terhormat atas kepercayaan bapak/ibu orang tua yang mempercayakan pendidikan putra-putrinya di lembaga kami. Anak usia dini adalah masa keemasan (golden age) di mana nilai-nilai karakter dasar harus ditanamkan secara kokoh.
        </p>
        <p class="text-fg-muted mb-6">
          Melalui kurikulum keislaman terpadu dan pembiasaan adab Islami setiap hari, kami berkomitmen mendampingi anak-anak tumbuh dengan cerdas, berakhlak mulia, ceria, serta memiliki kecintaan mendalam terhadap Al-Qur'an sejak usia dini. Mari bersama-sama kita sinergikan pendidikan di rumah dan di sekolah demi masa depan buah hati kita.
        </p>
        <p class="text-base font-bold text-primary">
          Hj. Siti Aminah, S.Pd.I
        </p>
        <p class="text-xs text-muted">
          Kepala Sekolah RA Attakal Yaqiin
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ======================== KEUNGGULAN SEKOLAH ======================== -->
<section class="section section--alt">
  <div class="container">
    <div class="section-header">
      <span class="section-label">Keunggulan</span>
      <h2 class="section-title">Mengapa Memilih <span>RA Attakal Yaqiin?</span></h2>
      <p class="section-subtitle">Kami berkomitmen memberikan lingkungan dan kualitas pendidikan terbaik untuk buah hati Anda.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" data-stagger="100">
      
      <!-- Keunggulan 1 -->
      <div class="card card-feature" data-animate="slide-up">
        <div class="card__icon">
          <i class="ti ti-book-2"></i>
        </div>
        <h3 class="card__title">Kurikulum Islami</h3>
        <p class="card__text">Integrasi Kurikulum Merdeka Belajar dengan nilai-nilai kepesantrenan dan pembiasaan adab harian.</p>
      </div>

      <!-- Keunggulan 2 -->
      <div class="card card-feature" data-animate="slide-up">
        <div class="card__icon">
          <i class="ti ti-users"></i>
        </div>
        <h3 class="card__title">Guru Berpengalaman</h3>
        <p class="card__text">Tenaga pengajar yang sabar, penyayang anak, tersertifikasi, dan hafal Al-Qur'an (tahfidz).</p>
      </div>

      <!-- Keunggulan 3 -->
      <div class="card card-feature" data-animate="slide-up">
        <div class="card__icon">
          <i class="ti ti-home-heart"></i>
        </div>
        <h3 class="card__title">Fasilitas Lengkap</h3>
        <p class="card__text">Ruang kelas ber-AC yang nyaman, taman bermain indoor & outdoor yang aman dan higienis.</p>
      </div>

      <!-- Keunggulan 4 -->
      <div class="card card-feature" data-animate="slide-up">
        <div class="card__icon">
          <i class="ti ti-certificate"></i>
        </div>
        <h3 class="card__title">Kegiatan Keagamaan</h3>
        <p class="card__text">Pembiasaan shalat dhuha berjamaah, hafalan doa harian, hadits pendek, dan manasik haji cilik.</p>
      </div>

    </div>
  </div>
</section>

<!-- ======================== STATISTIK SEKOLAH ======================== -->
<section class="stats">
  <div class="container">
    <div class="stats__grid">
      
      <!-- Stat 1 -->
      <div class="stats__item">
        <div class="stats__icon">
          <i class="ti ti-backpack"></i>
        </div>
        <div class="stats__number" data-count="120" data-count-suffix="+">0</div>
        <div class="stats__label">Siswa Aktif</div>
      </div>

      <!-- Stat 2 -->
      <div class="stats__item">
        <div class="stats__icon">
          <i class="ti ti-school-bell"></i>
        </div>
        <div class="stats__number" data-count="15" data-count-suffix="+">0</div>
        <div class="stats__label">Guru &amp; Staff</div>
      </div>

      <!-- Stat 3 -->
      <div class="stats__item">
        <div class="stats__icon">
          <i class="ti ti-star"></i>
        </div>
        <div class="stats__number" data-count="5" data-count-suffix="+">0</div>
        <div class="stats__label">Program Unggulan</div>
      </div>

      <!-- Stat 4 -->
      <div class="stats__item">
        <div class="stats__icon">
          <i class="ti ti-award"></i>
        </div>
        <div class="stats__number">A</div>
        <div class="stats__label">Akreditasi BAN-PDM</div>
      </div>

    </div>
  </div>
</section>

<!-- ======================== PROGRAM UNGGULAN ======================== -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <span class="section-label">Program Sekolah</span>
      <h2 class="section-title">Program Unggulan <span>Tumbuh Kembang</span></h2>
      <p class="section-subtitle">Program yang didesain khusus untuk melatih kemandirian, kecerdasan kognitif, dan kecintaan agama.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" data-stagger="100">
      
      <!-- Program 1 -->
      <div class="card card-program" data-animate="slide-up">
        <div class="card__body">
          <div class="card__icon">
            <i class="ti ti-book"></i>
          </div>
          <h3 class="card__title">Tahfidz Quran Cilik</h3>
          <p class="card__text">Hafalan Juz 30 metode Talaqqi yang menyenangkan, disertai arti dalam bentuk gerakan motorik anak.</p>
        </div>
      </div>

      <!-- Program 2 -->
      <div class="card card-program" data-animate="slide-up">
        <div class="card__body">
          <div class="card__icon">
            <i class="ti ti-pray"></i>
          </div>
          <h3 class="card__title">Praktek Ibadah Harian</h3>
          <p class="card__text">Pembiasaan wudhu, gerakan shalat berjamaah, zikir pagi-petang, serta doa harian aktivitas anak.</p>
        </div>
      </div>

      <!-- Program 3 -->
      <div class="card card-program" data-animate="slide-up">
        <div class="card__body">
          <div class="card__icon">
            <i class="ti ti-abc"></i>
          </div>
          <h3 class="card__title">Bahasa Arab &amp; Inggris</h3>
          <p class="card__text">Pengenalan kosa kata dasar bilingual melalui lagu-lagu interaktif dan kartu bergambar komunikatif.</p>
        </div>
      </div>

      <!-- Program 4 -->
      <div class="card card-program" data-animate="slide-up">
        <div class="card__body">
          <div class="card__icon">
            <i class="ti ti-palette"></i>
          </div>
          <h3 class="card__title">Seni &amp; Kaligrafi Dasar</h3>
          <p class="card__text">Mengasah kreativitas motorik halus dengan kegiatan mewarnai huruf hijaiyah dan kerajinan tangan Islami.</p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ======================== BERITA TERBARU ======================== -->
<section class="section section--alt">
  <div class="container">
    <div class="section-header">
      <span class="section-label">Berita &amp; Artikel</span>
      <h2 class="section-title">Kabar Terbaru dari <span>Sekolah</span></h2>
      <p class="section-subtitle">Ikuti perkembangan kegiatan belajar mengajar, pengumuman resmi, serta tips parenting Islami.</p>
    </div>

    <?php if (empty($latestNews)): ?>
      <p class="text-center text-muted">Belum ada berita terbaru yang diterbitkan.</p>
    <?php else: ?>
      <div class="news-grid" data-stagger="120">
        <?php foreach ($latestNews as $news): ?>
          <article class="card card-news" data-animate="slide-up">
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
            <div class="card__body">
              <span class="card__tag" style="background-color: <?= e($news['category_color'] ?? '#FFF0E5') ?>20; color: <?= e($news['category_color'] ?? '#E8600A') ?>;">
                <?= e($news['category_name'] ?? 'Umum') ?>
              </span>
              <div class="card__date">
                <i class="ti ti-calendar-event"></i> <?= format_date($news['published_at'], 'short') ?>
              </div>
              <h3 class="card__title">
                <a href="/berita/<?= e($news['slug']) ?>"><?= e($news['title']) ?></a>
              </h3>
              <p class="card__excerpt"><?= e(truncate($news['excerpt'] ?? $news['content'], 120)) ?></p>
              <a href="/berita/<?= e($news['slug']) ?>" class="card__read-more">
                Baca Selengkapnya <i class="ti ti-arrow-right"></i>
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <div class="text-center mt-12">
        <a href="/berita" class="btn btn-outline">Lihat Semua Berita <i class="ti ti-files"></i></a>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ======================== GALERI FOTO TERBARU ======================== -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <span class="section-label">Galeri Kegiatan</span>
      <h2 class="section-title">Dokumentasi <span>Aktivitas Anak</span></h2>
      <p class="section-subtitle">Potret kebersamaan, keceriaan, dan kekhusyukan anak-anak dalam mengikuti program pembelajaran.</p>
    </div>

    <?php if (empty($latestGallery)): ?>
      <p class="text-center text-muted">Belum ada dokumentasi foto.</p>
    <?php else: ?>
      <div class="gallery-grid" data-stagger="80">
        <?php foreach ($latestGallery as $i => $item): ?>
          <div class="gallery-item" 
               data-animate="scale-in"
               data-lightbox="home-gallery"
               data-lightbox-src="<?= upload_url($item['file_path']) ?>"
               data-lightbox-alt="<?= e($item['title']) . ($item['description'] ? ' - ' . e($item['description']) : '') ?>">
            <?php if (!empty($item['thumbnail_path']) && file_exists(BASE_PATH . '/' . $item['thumbnail_path'])): ?>
              <img src="<?= upload_url($item['thumbnail_path']) ?>" alt="<?= e($item['title']) ?>">
            <?php elseif (!empty($item['file_path']) && file_exists(BASE_PATH . '/' . $item['file_path'])): ?>
              <img src="<?= upload_url($item['file_path']) ?>" alt="<?= e($item['title']) ?>">
            <?php else: ?>
              <img src="https://placehold.co/400x400/FFF0E5/E8600A?text=<?= urlencode($item['title']) ?>" alt="<?= e($item['title']) ?>">
            <?php endif; ?>
            <div class="gallery-item__overlay">
              <div class="gallery-item__overlay-icon">
                <i class="ti ti-zoom-in" style="font-size: 24px;"></i>
              </div>
              <div class="gallery-item__caption"><?= e($item['title']) ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="text-center mt-12">
        <a href="/galeri" class="btn btn-outline">Lihat Galeri Lengkap <i class="ti ti-photo"></i></a>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ======================== TESTIMONI ORANG TUA ======================== -->
<section class="section section--alt">
  <div class="container">
    <div class="section-header">
      <span class="section-label">Testimoni</span>
      <h2 class="section-title">Apa Kata <span>Orang Tua Murid?</span></h2>
      <p class="section-subtitle">Ungkapan hati dan ulasan dari bapak/ibu yang telah mempercayakan tumbuh kembang putra-putrinya kepada kami.</p>
    </div>

    <!-- Testimonial Grid/Slider (simple grid but elegant layout) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-stagger="120">
      
      <!-- Testi 1 -->
      <div class="testimonial-card" data-animate="slide-up">
        <svg class="testimonial-card__quote-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11.19 12.19c-.31-.32-.44-.73-.39-1.25.08-.85.73-1.42 1.48-1.42h1.44c-.26-.95-1.12-1.63-2.12-1.63H9.44c-.55 0-1-.45-1-1v-.8c0-.55.45-1 1-1h2.16c2.42 0 4.38 1.96 4.38 4.38v3.72c0 .55-.45 1-1 1h-2.82c-.55 0-1.02-.27-1.47-.72zm-7 0c-.31-.32-.44-.73-.39-1.25.08-.85.73-1.42 1.48-1.42H6.7c-.26-.95-1.12-1.63-2.12-1.63H2.44c-.55 0-1-.45-1-1v-.8c0-.55.45-1 1-1h2.16c2.42 0 4.38 1.96 4.38 4.38v3.72c0 .55-.45 1-1 1H6.17c-.55 0-1.02-.27-1.47-.72z"/></svg>
        <div class="testimonial-card__rating">
          <i class="ti ti-star-filled testimonial-card__star"></i>
          <i class="ti ti-star-filled testimonial-card__star"></i>
          <i class="ti ti-star-filled testimonial-card__star"></i>
          <i class="ti ti-star-filled testimonial-card__star"></i>
          <i class="ti ti-star-filled testimonial-card__star"></i>
        </div>
        <p class="testimonial-card__text">
          "Alhamdulillah, sejak sekolah di RA Attakal Yaqiin, anak saya sekarang jadi lebih mandiri dan sangat rajin shalat dhuha. Di rumah dia sering melafalkan hafalan surat-surat pendek dengan lancar. Gurunya ramah dan sabar."
        </p>
        <div class="testimonial-card__author">
          <img class="testimonial-card__avatar" src="https://placehold.co/100x100/FFF0E5/E8600A?text=Ibu+Siti" alt="Bunda Raisa">
          <div>
            <div class="testimonial-card__name">Bunda Raisa</div>
            <div class="testimonial-card__role">Orang Tua dari Raisa (Kelompok B1)</div>
          </div>
        </div>
      </div>

      <!-- Testi 2 -->
      <div class="testimonial-card" data-animate="slide-up">
        <svg class="testimonial-card__quote-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11.19 12.19c-.31-.32-.44-.73-.39-1.25.08-.85.73-1.42 1.48-1.42h1.44c-.26-.95-1.12-1.63-2.12-1.63H9.44c-.55 0-1-.45-1-1v-.8c0-.55.45-1 1-1h2.16c2.42 0 4.38 1.96 4.38 4.38v3.72c0 .55-.45 1-1 1h-2.82c-.55 0-1.02-.27-1.47-.72zm-7 0c-.31-.32-.44-.73-.39-1.25.08-.85.73-1.42 1.48-1.42H6.7c-.26-.95-1.12-1.63-2.12-1.63H2.44c-.55 0-1-.45-1-1v-.8c0-.55.45-1 1-1h2.16c2.42 0 4.38 1.96 4.38 4.38v3.72c0 .55-.45 1-1 1H6.17c-.55 0-1.02-.27-1.47-.72z"/></svg>
        <div class="testimonial-card__rating">
          <i class="ti ti-star-filled testimonial-card__star"></i>
          <i class="ti ti-star-filled testimonial-card__star"></i>
          <i class="ti ti-star-filled testimonial-card__star"></i>
          <i class="ti ti-star-filled testimonial-card__star"></i>
          <i class="ti ti-star-filled testimonial-card__star"></i>
        </div>
        <p class="testimonial-card__text">
          "Sangat puas menyekolahkan anak di sini. Fasilitasnya lengkap, bersih dan ramah anak. Program tahfidz qurannya berjalan efektif, anak saya senang sekali menghafal sambil bermain dengan ustadzahnya. Highly recommended!"
        </p>
        <div class="testimonial-card__author">
          <img class="testimonial-card__avatar" src="https://placehold.co/100x100/FFF0E5/E8600A?text=Bapak+Ahmad" alt="Ayah Fatih">
          <div>
            <div class="testimonial-card__name">Ayah Fatih</div>
            <div class="testimonial-card__role">Orang Tua dari Fatih (Kelompok A2)</div>
          </div>
        </div>
      </div>

      <!-- Testi 3 -->
      <div class="testimonial-card" data-animate="slide-up">
        <svg class="testimonial-card__quote-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11.19 12.19c-.31-.32-.44-.73-.39-1.25.08-.85.73-1.42 1.48-1.42h1.44c-.26-.95-1.12-1.63-2.12-1.63H9.44c-.55 0-1-.45-1-1v-.8c0-.55.45-1 1-1h2.16c2.42 0 4.38 1.96 4.38 4.38v3.72c0 .55-.45 1-1 1h-2.82c-.55 0-1.02-.27-1.47-.72zm-7 0c-.31-.32-.44-.73-.39-1.25.08-.85.73-1.42 1.48-1.42H6.7c-.26-.95-1.12-1.63-2.12-1.63H2.44c-.55 0-1-.45-1-1v-.8c0-.55.45-1 1-1h2.16c2.42 0 4.38 1.96 4.38 4.38v3.72c0 .55-.45 1-1 1H6.17c-.55 0-1.02-.27-1.47-.72z"/></svg>
        <div class="testimonial-card__rating">
          <i class="ti ti-star-filled testimonial-card__star"></i>
          <i class="ti ti-star-filled testimonial-card__star"></i>
          <i class="ti ti-star-filled testimonial-card__star"></i>
          <i class="ti ti-star-filled testimonial-card__star"></i>
          <i class="ti ti-star-filled testimonial-card__star"></i>
        </div>
        <p class="testimonial-card__text">
          "Perkembangan emosional anak saya sangat positif. Guru pendamping selalu memberikan laporan harian dengan detail. Anak jadi lebih sopan kepada orang tua dan tahu tata cara makan dan tidur sesuai sunnah Rasulullah."
        </p>
        <div class="testimonial-card__author">
          <img class="testimonial-card__avatar" src="https://placehold.co/100x100/FFF0E5/E8600A?text=Ibu+Rina" alt="Bunda Aisyah">
          <div>
            <div class="testimonial-card__name">Bunda Aisyah</div>
            <div class="testimonial-card__role">Orang Tua dari Aisyah (Kelompok B2)</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ======================== LOKASI / GOOGLE MAPS ======================== -->
<section class="section p-0">
  <div class="w-full" style="height: 450px; overflow:hidden;" data-animate="fade-in">
    <!-- Google Maps Embed (Realistic Location: Ciputat, Tangerang Selatan) -->
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.748366472251!2d106.7410313!3d-6.3023243!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f018e6a5b42d%3A0x6b772b123456789!2sJl.%20Pendidikan%2C%20Ciputat%2C%20Tangerang%20Selatan!5e0!3m2!1sid!2sid!4v1718000000000!5m2!1sid!2sid" 
      width="100%" 
      height="100%" 
      style="border:0;" 
      allowfullscreen="" 
      loading="lazy" 
      referrerpolicy="no-referrer-when-downgrade"
      title="Lokasi RA Attakal Yaqiin">
    </iframe>
  </div>
</section>
