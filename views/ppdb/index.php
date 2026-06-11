<?php
/**
 * Halaman Pendaftaran PPDB Online
 * RA Attakal Yaqiin
 *
 * Expected variables:
 *   $settings    - array of PPDB configuration settings
 *   $isOpen      - boolean, true if registration is open
 *   $flash       - array|null, flash success/error messages
 */

$tahunAjaran = $settings['tahun_ajaran'] ?? '2025/2026';
$whatsapp = $settings['site_whatsapp'] ?? '6281234567890';
$phone = $settings['site_phone'] ?? '(021) 1234-5678';
?>

<!-- Breadcrumbs -->
<?php require BASE_PATH . '/views/partials/breadcrumb.php'; ?>

<!-- Page Header -->
<section class="section py-12 bg-surface">
  <div class="container">
    <div data-animate="fade-in">
      <span class="text-primary font-semibold uppercase tracking-wider text-sm block mb-2">Pendaftaran Siswa Baru</span>
      <h1 class="text-4xl font-extrabold tracking-tight text-fg mb-4">PPDB Online TA <?= e($tahunAjaran) ?></h1>
      <p class="text-lg text-muted max-w-2xl">Mari bergabung bersama keluarga besar RA Attakal Yaqiin. Bentuk karakter mulia dan cinta Al-Quran buah hati Anda sejak dini.</p>
    </div>
  </div>
</section>

<!-- Main Section -->
<section class="section">
  <div class="container">

    <!-- Flash Alerts -->
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

    <?php if (!$isOpen): ?>
      <!-- ==================== PPDB CLOSED VIEW ==================== -->
      <div class="coming-soon bg-card border rounded p-8 md:p-16 text-center max-w-3xl mx-auto shadow-sm" data-animate="scale-in">
        <div class="coming-soon__icon mb-6" style="display:inline-flex; width: 80px; height: 80px; border-radius: 50%; background-color: var(--primary-lighter); align-items:center; justify-content:center; color: var(--primary);">
          <i class="ti ti-lock" style="font-size: 40px;"></i>
        </div>
        <h2 class="text-2xl md:text-3xl font-extrabold text-fg mb-4">Pendaftaran Online Belum Dibuka</h2>
        <p class="text-fg-muted mb-8 max-w-lg mx-auto" style="line-height: 1.6;">
          Mohon maaf, saat ini Penerimaan Peserta Didik Baru (PPDB) online untuk Tahun Ajaran <?= e($tahunAjaran) ?> belum dibuka atau sudah berakhir.
        </p>

        <div class="bg-surface border rounded p-6 mb-8 text-left max-w-md mx-auto">
          <h3 class="text-sm font-bold text-fg mb-3 border-b pb-2"><i class="ti ti-calendar-event text-primary"></i> Perkiraan Jadwal PPDB</h3>
          <ul class="flex flex-col gap-2 text-xs text-muted" style="list-style:none; padding:0;">
            <li class="flex justify-between"><span>Gelombang 1 (Dini):</span> <strong>15 Jan - 28 Feb</strong></li>
            <li class="flex justify-between"><span>Gelombang 2 (Reguler):</span> <strong>1 Mar - 30 Apr</strong></li>
            <li class="flex justify-between"><span>Gelombang 3 (Sisa Kuota):</span> <strong>1 Mei - 30 Jun</strong></li>
          </ul>
        </div>

        <p class="text-xs text-muted mb-4">Butuh informasi lebih lanjut mengenai pendaftaran offline atau biaya masuk?</p>
        <div class="flex flex-wrap gap-4 justify-center">
          <a href="https://wa.me/<?= e($whatsapp) ?>?text=Assalamualaikum%20panitia%20PPDB" target="_blank" rel="noopener" class="btn btn-whatsapp">
            <i class="ti ti-brand-whatsapp"></i> Chat Panitia PPDB
          </a>
          <a href="/kontak" class="btn btn-outline">Hubungi Kami <i class="ti ti-phone"></i></a>
        </div>
      </div>

    <?php else: ?>
      <!-- ==================== PPDB OPEN VIEW ==================== -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Requirements Info (Spans 4 cols) -->
        <div class="lg:col-span-4" data-animate="slide-up">
          <div class="bg-card border rounded p-6 shadow-sm sticky" style="top: calc(var(--navbar-height) + var(--space-4));">
            <h3 class="text-base font-bold text-fg border-b pb-3 mb-4"><i class="ti ti-checklist text-primary"></i> Ketentuan &amp; Syarat</h3>
            
            <div class="flex flex-col gap-6 text-xs text-fg-muted">
              
              <div>
                <h4 class="font-bold text-fg mb-1">1. Batasan Usia Calon Siswa</h4>
                <ul class="pl-4 mt-1 flex flex-col gap-1" style="list-style-type:disc;">
                  <li><strong>Kelompok A:</strong> Usia 4 - 5 tahun per Juli 2026.</li>
                  <li><strong>Kelompok B:</strong> Usia 5 - 6 tahun per Juli 2026.</li>
                </ul>
              </div>

              <div>
                <h4 class="font-bold text-fg mb-1">2. Berkas yang Harus Disiapkan</h4>
                <p class="mb-1">Dokumen berikut diserahkan ke sekolah saat verifikasi berkas/pembayaran:</p>
                <ul class="pl-4 flex flex-col gap-1" style="list-style-type:disc;">
                  <li>Fotokopi Akta Kelahiran Anak (2 Lembar)</li>
                  <li>Fotokopi Kartu Keluarga (2 Lembar)</li>
                  <li>Fotokopi KTP Ayah &amp; Ibu (2 Lembar)</li>
                  <li>Pas Foto Anak 3x4 Latar Merah (4 Lembar)</li>
                </ul>
              </div>

              <div>
                <h4 class="font-bold text-fg mb-1">3. Alur Pendaftaran Online</h4>
                <ol class="pl-4 flex flex-col gap-1" style="list-style-type:decimal;">
                  <li>Isi formulir pendaftaran di sebelah kanan secara lengkap.</li>
                  <li>Kirim formulir secara online.</li>
                  <li>Panitia PPDB akan menghubungi Anda untuk konfirmasi &amp; penjadwalan wawancara/observasi anak.</li>
                  <li>Lakukan verifikasi berkas &amp; administrasi langsung di sekolah.</li>
                </ol>
              </div>

              <div class="pt-4 border-t">
                <p class="font-semibold text-fg mb-2">Ada Kendala Pendaftaran?</p>
                <a href="https://wa.me/<?= e($whatsapp) ?>?text=Assalamualaikum%2C%20saya%20mengalami%20kendala%20saat%20mengisi%20formulir%20PPDB%20online" 
                   target="_blank" rel="noopener" 
                   class="btn btn-sm btn-whatsapp w-full justify-center">
                  <i class="ti ti-brand-whatsapp"></i> WhatsApp Panitia
                </a>
              </div>

            </div>
          </div>
        </div>

        <!-- Right: Registration Form (Spans 8 cols) -->
        <div class="lg:col-span-8" data-animate="slide-left">
          <div class="bg-card border rounded p-6 md:p-10 shadow-sm">
            <h2 class="text-xl font-bold text-fg mb-1">Formulir Pendaftaran Siswa Baru</h2>
            <p class="text-xs text-muted mb-6">Silakan lengkapi data calon peserta didik dan data orang tua/wali dengan data yang benar.</p>
            
            <form action="/ppdb" method="POST">
              <?= csrf_field() ?>
              
              <!-- SECTION A: DATA ANAK -->
              <h3 class="text-xs font-bold text-primary uppercase tracking-wider mb-4 pb-1 border-b" style="border-color: var(--primary-lighter);"><i class="ti ti-user-check"></i> A. Data Calon Siswa</h3>
              
              <!-- Nama Lengkap Anak -->
              <div class="form-group mb-4">
                <label for="child_name" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">Nama Lengkap Anak <span class="text-danger" style="color:red;">*</span></label>
                <input type="text" name="child_name" id="child_name" class="form-control" placeholder="Contoh: Muhammad Alkhalifi" required>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <!-- Jenis Kelamin -->
                <div class="form-group">
                  <label for="gender" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">Jenis Kelamin <span class="text-danger" style="color:red;">*</span></label>
                  <select name="gender" id="gender" class="form-control" required>
                    <option value="" disabled selected>-- Pilih --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                  </select>
                </div>

                <!-- Tempat Lahir -->
                <div class="form-group">
                  <label for="birth_place" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">Tempat Lahir <span class="text-danger" style="color:red;">*</span></label>
                  <input type="text" name="birth_place" id="birth_place" class="form-control" placeholder="Contoh: Tangerang" required>
                </div>

                <!-- Tanggal Lahir -->
                <div class="form-group">
                  <label for="birth_date" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">Tanggal Lahir <span class="text-danger" style="color:red;">*</span></label>
                  <input type="date" name="birth_date" id="birth_date" class="form-control" required>
                </div>
              </div>

              <!-- SECTION B: DATA ORANG TUA -->
              <h3 class="text-xs font-bold text-primary uppercase tracking-wider mb-4 mt-8 pb-1 border-b" style="border-color: var(--primary-lighter);"><i class="ti ti-users"></i> B. Data Orang Tua / Wali</h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Nama Ayah -->
                <div class="form-group">
                  <label for="father_name" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">Nama Lengkap Ayah Kandung <span class="text-danger" style="color:red;">*</span></label>
                  <input type="text" name="father_name" id="father_name" class="form-control" placeholder="Contoh: Hermawan" required>
                </div>

                <!-- Nama Ibu -->
                <div class="form-group">
                  <label for="mother_name" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">Nama Lengkap Ibu Kandung <span class="text-danger" style="color:red;">*</span></label>
                  <input type="text" name="mother_name" id="mother_name" class="form-control" placeholder="Contoh: Fatimah" required>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Telepon / WhatsApp -->
                <div class="form-group">
                  <label for="parent_phone" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">No. WhatsApp / Telepon Aktif <span class="text-danger" style="color:red;">*</span></label>
                  <input type="text" name="parent_phone" id="parent_phone" class="form-control" placeholder="Contoh: 0812XXXXXXXX (Utamakan WA)" required>
                </div>

                <!-- Email Orang Tua -->
                <div class="form-group">
                  <label for="parent_email" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">Email Orang Tua <span class="text-danger" style="color:red;">*</span></label>
                  <input type="email" name="parent_email" id="parent_email" class="form-control" placeholder="Contoh: orangtua@gmail.com" required>
                </div>
              </div>

              <!-- Alamat Rumah -->
              <div class="form-group mb-4">
                <label for="address" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">Alamat Lengkap Rumah <span class="text-danger" style="color:red;">*</span></label>
                <textarea name="address" id="address" rows="3" class="form-control" placeholder="Tuliskan alamat lengkap tempat tinggal saat ini (Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan, Kota)" required style="resize:vertical; min-height: 80px;"></textarea>
              </div>

              <!-- SECTION C: INFORMASI LAINNYA -->
              <h3 class="text-xs font-bold text-primary uppercase tracking-wider mb-4 mt-8 pb-1 border-b" style="border-color: var(--primary-lighter);"><i class="ti ti-notes"></i> C. Catatan Khusus (Opsional)</h3>
              
              <!-- Catatan Khusus -->
              <div class="form-group mb-6">
                <label for="notes" class="form-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display:block;">Informasi Tambahan / Riwayat Kesehatan Anak</label>
                <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Tuliskan jika anak memiliki riwayat kesehatan khusus, alergi, bakat tertentu, atau kebutuhan khusus lainnya..." style="resize:vertical; min-height: 80px;"></textarea>
              </div>

              <!-- Submit Button -->
              <div class="pt-2">
                <button type="submit" class="btn btn-primary w-full md:w-auto">
                  Kirim Pendaftaran Online <i class="ti ti-clipboard-list" style="margin-left: 6px;"></i>
                </button>
              </div>

            </form>
          </div>
        </div>

      </div>
    <?php endif; ?>

  </div>
</section>
