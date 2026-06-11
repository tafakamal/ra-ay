<?php
/**
 * View Pengaturan Website
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<div class="panel-card" style="max-width: 900px; margin: 0 auto;">
    <div class="panel-header" style="border-bottom: none; padding-bottom: 0;">
        <h2 class="panel-title"><i class="ti ti-settings"></i> Pengaturan Website</h2>
    </div>

    <!-- Tabs Navigation -->
    <div class="tabs-wrapper" style="border-bottom: 1px solid var(--border); margin: 0 1.5rem;">
        <ul class="tabs-nav" style="display: flex; list-style: none; gap: 1rem; padding: 0; margin: 0;">
            <li class="tab-item">
                <button type="button" class="tab-btn active" data-tab="general" style="background: none; border: none; padding: 1rem 0.5rem; font-weight: 600; font-size: 0.95rem; color: var(--text-muted); cursor: pointer; border-bottom: 2px solid transparent; display: flex; align-items: center; gap: 0.25rem;">
                    <i class="ti ti-info-circle"></i> Informasi Umum
                </button>
            </li>
            <li class="tab-item">
                <button type="button" class="tab-btn" data-tab="contact" style="background: none; border: none; padding: 1rem 0.5rem; font-weight: 600; font-size: 0.95rem; color: var(--text-muted); cursor: pointer; border-bottom: 2px solid transparent; display: flex; align-items: center; gap: 0.25rem;">
                    <i class="ti ti-phone"></i> Informasi Kontak
                </button>
            </li>
            <li class="tab-item">
                <button type="button" class="tab-btn" data-tab="social" style="background: none; border: none; padding: 1rem 0.5rem; font-weight: 600; font-size: 0.95rem; color: var(--text-muted); cursor: pointer; border-bottom: 2px solid transparent; display: flex; align-items: center; gap: 0.25rem;">
                    <i class="ti ti-brand-instagram"></i> Media Sosial
                </button>
            </li>
        </ul>
    </div>

    <div class="panel-body" style="padding-top: 1.5rem;">
        <form action="<?= url('/admin/settings/update') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Tab 1: Informasi Umum -->
            <div class="tab-content active" id="tab-general">
                <div class="form-group">
                    <label for="site_name" class="form-label">Nama Website / Sekolah</label>
                    <input type="text" name="site_name" id="site_name" class="form-control" value="<?= e($settings['site_name'] ?? 'Raudhatul Athfal (RA) Attakal Yaqiin') ?>" placeholder="Nama Lembaga / Sekolah">
                </div>

                <div class="form-group">
                    <label for="tagline" class="form-label">Slogan / Tagline Website</label>
                    <input type="text" name="tagline" id="tagline" class="form-control" value="<?= e($settings['tagline'] ?? '') ?>" placeholder="Contoh: Berakhlaq Mulia, Cerdas, dan Kreatif">
                    <span class="form-text">Akan muncul di halaman beranda website.</span>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="description" class="form-label">Deskripsi Singkat Sekolah</label>
                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="Tulis deskripsi singkat sekolah Anda..."><?= e($settings['description'] ?? '') ?></textarea>
                    <span class="form-text">Penjelasan singkat tentang sekolah yang akan ditampilkan di bagian footer/tentang beranda.</span>
                </div>
            </div>

            <!-- Tab 2: Informasi Kontak -->
            <div class="tab-content" id="tab-contact" style="display: none;">
                <div class="form-row form-row-2">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Sekolah</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= e($settings['email'] ?? '') ?>" placeholder="sekolah@email.com">
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Nomor Telepon / HP / WhatsApp</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="<?= e($settings['phone'] ?? '') ?>" placeholder="Contoh: 081234567890">
                    </div>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Alamat Lengkap Sekolah</label>
                    <textarea name="address" id="address" class="form-control" rows="3" placeholder="Masukkan alamat jalan, RT/RW, kelurahan, kecamatan, kabupaten/kota..."><?= e($settings['address'] ?? '') ?></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="map" class="form-label">Google Maps Embed Link (Iframe Src)</label>
                    <textarea name="map" id="map" class="form-control" rows="3" placeholder="Contoh: https://www.google.com/maps/embed?pb=!1m18!1m12!..."><?= e($settings['map'] ?? '') ?></textarea>
                    <span class="form-text">Masukkan hanya bagian URL atribut `src` dari kode embed Google Maps.</span>
                </div>
            </div>

            <!-- Tab 3: Media Sosial -->
            <div class="tab-content" id="tab-social" style="display: none;">
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.9rem;">
                    Masukkan tautan akun media sosial sekolah Anda. Ikon media sosial akan muncul di bagian header dan footer website.
                </p>

                <div class="form-group">
                    <label for="facebook" class="form-label"><i class="ti ti-brand-facebook" style="color: #1877F2;"></i> Link Facebook</label>
                    <input type="url" name="facebook" id="facebook" class="form-control" value="<?= e($settings['facebook'] ?? '') ?>" placeholder="https://facebook.com/nama-akun">
                </div>

                <div class="form-group">
                    <label for="instagram" class="form-label"><i class="ti ti-brand-instagram" style="color: #E1306C;"></i> Link Instagram</label>
                    <input type="url" name="instagram" id="instagram" class="form-control" value="<?= e($settings['instagram'] ?? '') ?>" placeholder="https://instagram.com/nama-akun">
                </div>

                <div class="form-group">
                    <label for="youtube" class="form-label"><i class="ti ti-brand-youtube" style="color: #FF0000;"></i> Link Channel YouTube</label>
                    <input type="url" name="youtube" id="youtube" class="form-control" value="<?= e($settings['youtube'] ?? '') ?>" placeholder="https://youtube.com/c/nama-channel">
                </div>

                <div class="form-group">
                    <label for="tiktok" class="form-label"><i class="ti ti-brand-tiktok" style="color: #000000;"></i> Link TikTok</label>
                    <input type="url" name="tiktok" id="tiktok" class="form-control" value="<?= e($settings['tiktok'] ?? '') ?>" placeholder="https://tiktok.com/@nama-akun">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="whatsapp" class="form-label"><i class="ti ti-brand-whatsapp" style="color: #25D366;"></i> Nomor WhatsApp Penerima Chat</label>
                    <input type="text" name="whatsapp" id="whatsapp" class="form-control" value="<?= e($settings['whatsapp'] ?? '') ?>" placeholder="Contoh: 6281234567890">
                    <span class="form-text">Gunakan format kode negara (62) tanpa spasi atau tanda hubung.</span>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Simpan Semua Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tab JS Logic -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const targetTab = btn.dataset.tab;

                // Deactivate all buttons
                tabBtns.forEach(b => {
                    b.classList.remove('active');
                    b.style.borderColor = 'transparent';
                    b.style.color = 'var(--text-muted)';
                });

                // Hide all contents
                tabContents.forEach(c => {
                    c.style.display = 'none';
                });

                // Activate clicked button
                btn.classList.add('active');
                btn.style.borderColor = 'var(--primary)';
                btn.style.color = 'var(--primary)';

                // Show target content
                document.getElementById('tab-' + targetTab).style.display = 'block';
            });
        });

        // Initialize active tab styling
        const activeBtn = document.querySelector('.tab-btn.active');
        if (activeBtn) {
            activeBtn.style.borderColor = 'var(--primary)';
            activeBtn.style.color = 'var(--primary)';
        }
    });
</script>
