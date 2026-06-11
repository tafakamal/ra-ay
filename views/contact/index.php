<?php
/**
 * Halaman Kontak Kami
 * RA Attakal Yaqiin
 *
 * Expected variables:
 *   $settings - array of contact details and social media settings
 *   $flash    - array|null, flash message (type, message) if redirected
 */
?>

<!-- Breadcrumbs -->
<?php require BASE_PATH . '/views/partials/breadcrumb.php'; ?>

<!-- Page Header -->
<section class="section py-12 bg-surface">
  <div class="container">
    <div data-animate="fade-in">
      <span class="text-primary font-semibold uppercase tracking-wider text-sm block mb-2">Hubungi Kami</span>
      <h1 class="text-4xl font-extrabold tracking-tight text-fg mb-4">Ada Pertanyaan? Hubungi Kami</h1>
      <p class="text-lg text-muted max-w-2xl">Kami siap memberikan informasi lengkap seputar pembelajaran, pendaftaran siswa baru (PPDB), maupun kemitraan.</p>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section class="section">
  <div class="container">
    
    <!-- Flash Messages -->
    <?php if (!empty($flash)): ?>
      <div class="mb-8 p-4 rounded border" 
           style="background-color: <?= $flash['type'] === 'success' ? '#DEF7EC' : '#FDE8E8' ?>; 
                  border-color: <?= $flash['type'] === 'success' ? '#31C48D' : '#F8B4B4' ?>; 
                  color: <?= $flash['type'] === 'success' ? '#03543F' : '#9B1C1C' ?>;
                  display: flex; align-items: center; gap: 8px;"
           data-animate="fade-in">
        <i class="ti <?= $flash['type'] === 'success' ? 'ti-circle-check' : 'ti-alert-circle' ?>" style="font-size: 20px;"></i>
        <span class="text-sm font-semibold"><?= e($flash['message']) ?></span>
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
      
      <!-- Left Column: Contact info & Maps (Spans 5 cols) -->
      <div class="lg:col-span-5" data-animate="slide-up">
        
        <!-- Info Cards -->
        <div class="bg-card border rounded p-6 shadow-sm mb-6 flex flex-col gap-6">
          <h3 class="text-base font-bold text-fg border-b pb-3 mb-2">Informasi Sekolah</h3>
          
          <!-- Alamat -->
          <div class="flex gap-4">
            <div class="text-primary flex-shrink-0" style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--primary-lighter); display: flex; align-items: center; justify-content: center;">
              <i class="ti ti-map-pin" style="font-size: 20px;"></i>
            </div>
            <div>
              <h4 class="text-sm font-bold text-fg mb-1">Alamat Utama</h4>
              <p class="text-xs text-muted" style="line-height: 1.5;"><?= e($settings['site_address'] ?? 'Jl. Pendidikan No. 10, Tangerang Selatan') ?></p>
            </div>
          </div>

          <!-- Telepon -->
          <div class="flex gap-4">
            <div class="text-primary flex-shrink-0" style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--primary-lighter); display: flex; align-items: center; justify-content: center;">
              <i class="ti ti-phone" style="font-size: 20px;"></i>
            </div>
            <div>
              <h4 class="text-sm font-bold text-fg mb-1">Telepon Sekolah</h4>
              <p class="text-xs text-muted"><?= e($settings['site_phone'] ?? '(021) 1234-5678') ?></p>
            </div>
          </div>

          <!-- WhatsApp -->
          <div class="flex gap-4">
            <div class="text-primary flex-shrink-0" style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--primary-lighter); display: flex; align-items: center; justify-content: center;">
              <i class="ti ti-brand-whatsapp" style="font-size: 20px;"></i>
            </div>
            <div>
              <h4 class="text-sm font-bold text-fg mb-1">WhatsApp Fast Response</h4>
              <p class="text-xs text-muted">
                <a href="https://wa.me/<?= e($settings['site_whatsapp'] ?? '6281234567890') ?>?text=Assalamualaikum" target="_blank" rel="noopener" class="text-primary hover:underline">
                  +<?= e($settings['site_whatsapp'] ?? '6281234567890') ?>
                </a>
              </p>
            </div>
          </div>

          <!-- Email -->
          <div class="flex gap-4">
            <div class="text-primary flex-shrink-0" style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--primary-lighter); display: flex; align-items: center; justify-content: center;">
              <i class="ti ti-mail" style="font-size: 20px;"></i>
            </div>
            <div>
              <h4 class="text-sm font-bold text-fg mb-1">E-mail Resmi</h4>
              <p class="text-xs text-muted"><?= e($settings['site_email'] ?? 'info@raattakalyaqiin.com') ?></p>
            </div>
          </div>

        </div>

        <!-- Social Media Accounts -->
        <div class="bg-card border rounded p-6 shadow-sm mb-6">
          <h3 class="text-sm font-bold text-fg uppercase tracking-wider mb-4">Media Sosial Kami</h3>
          <div class="flex gap-3">
            <?php if (!empty($settings['social_facebook'])): ?>
              <a href="<?= e($settings['social_facebook']) ?>" target="_blank" rel="noopener" class="text-muted hover:text-primary transition" style="font-size: 24px;" aria-label="Facebook"><i class="ti ti-brand-facebook"></i></a>
            <?php endif; ?>
            <?php if (!empty($settings['social_instagram'])): ?>
              <a href="<?= e($settings['social_instagram']) ?>" target="_blank" rel="noopener" class="text-muted hover:text-primary transition" style="font-size: 24px;" aria-label="Instagram"><i class="ti ti-brand-instagram"></i></a>
            <?php endif; ?>
            <?php if (!empty($settings['social_youtube'])): ?>
              <a href="<?= e($settings['social_youtube']) ?>" target="_blank" rel="noopener" class="text-muted hover:text-primary transition" style="font-size: 24px;" aria-label="YouTube"><i class="ti ti-brand-youtube"></i></a>
            <?php endif; ?>
            <?php if (!empty($settings['social_tiktok'])): ?>
              <a href="<?= e($settings['social_tiktok']) ?>" target="_blank" rel="noopener" class="text-muted hover:text-primary transition" style="font-size: 24px;" aria-label="TikTok"><i class="ti ti-brand-tiktok"></i></a>
            <?php endif; ?>
          </div>
        </div>

        <!-- Google Maps Embed -->
        <?php if (!empty($settings['site_maps_embed'])): ?>
          <div class="rounded border overflow-hidden shadow-sm" style="height: 250px;">
            <iframe 
              src="<?= e($settings['site_maps_embed']) ?>" 
              width="100%" 
              height="100%" 
              style="border:0;" 
              allowfullscreen="" 
              loading="lazy" 
              referrerpolicy="no-referrer-when-downgrade"
              title="Lokasi RA Attakal Yaqiin di Google Maps"
            ></iframe>
          </div>
        <?php endif; ?>

      </div>

      <!-- Right Column: Contact Form (Spans 7 cols) -->
      <div class="lg:col-span-7" data-animate="slide-left">
        <div class="bg-card border rounded p-6 md:p-10 shadow-sm">
          <h3 class="text-lg font-bold text-fg mb-2">Kirim Pesan Langsung</h3>
          <p class="text-xs text-muted mb-6">Silakan isi formulir di bawah ini dengan lengkap. Tim administrator kami akan segera membalas pesan Anda.</p>
          
          <form action="/kontak" method="POST">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Nama Lengkap -->
              <div class="form-group">
                <label for="name" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">Nama Lengkap <span class="text-danger" style="color:red;">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Ahmad Fauzi" required>
              </div>

              <!-- Email -->
              <div class="form-group">
                <label for="email" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">Alamat Email <span class="text-danger" style="color:red;">*</span></label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Contoh: ahmad@gmail.com" required>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
              <!-- Telepon/WA -->
              <div class="form-group">
                <label for="phone" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">No. Telepon / WhatsApp</label>
                <input type="text" name="phone" id="phone" class="form-control" placeholder="Contoh: 0812XXXXXXXX">
              </div>

              <!-- Subjek -->
              <div class="form-group">
                <label for="subject" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">Subjek Pesan <span class="text-danger" style="color:red;">*</span></label>
                <input type="text" name="subject" id="subject" class="form-control" placeholder="Contoh: Tanya Pendaftaran Siswa Baru" required>
              </div>
            </div>

            <!-- Pesan -->
            <div class="form-group mt-4">
              <label for="message" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">Isi Pesan <span class="text-danger" style="color:red;">*</span></label>
              <textarea name="message" id="message" rows="5" class="form-control" placeholder="Tuliskan detail pertanyaan atau pesan Anda di sini..." required style="resize:vertical; min-height: 120px;"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
              <button type="submit" class="btn btn-primary w-full md:w-auto">
                Kirim Pesan <i class="ti ti-send" style="margin-left: 6px;"></i>
              </button>
            </div>

          </form>
        </div>
      </div>

    </div>

  </div>
</section>
