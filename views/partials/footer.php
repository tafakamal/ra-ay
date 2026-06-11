<?php
/**
 * footer.php — Premium Footer
 * RA Attakal Yaqiin
 */
?>

<!-- ======================== FOOTER ======================== -->
<footer class="footer" role="contentinfo">

  <!-- Islamic ornament border -->
  <div class="footer__ornament" aria-hidden="true"></div>

  <div class="footer__main">
    <div class="container">
      <div class="footer__grid">

        <!-- Column 1: About -->
        <div class="footer__about">
          <div class="footer__logo-wrapper">
            <img
              src="/assets/images/logo.png"
              alt="Logo RA Attakal Yaqiin"
              class="footer__logo"
              width="48"
              height="48"
              loading="lazy"
            >
            <span class="footer__brand-name">RA Attakal Yaqiin</span>
          </div>
          <p class="footer__desc">
            Raudhatul Athfal Attakal Yaqiin adalah lembaga pendidikan anak usia dini berbasis Islam
            yang berkomitmen membentuk generasi Qurani, berakhlak mulia, dan cerdas sejak dini.
          </p>
          <div class="footer__social">
            <a href="#" class="footer__social-link" aria-label="Facebook" target="_blank" rel="noopener">
              <i class="ti ti-brand-facebook"></i>
            </a>
            <a href="#" class="footer__social-link" aria-label="Instagram" target="_blank" rel="noopener">
              <i class="ti ti-brand-instagram"></i>
            </a>
            <a href="#" class="footer__social-link" aria-label="YouTube" target="_blank" rel="noopener">
              <i class="ti ti-brand-youtube"></i>
            </a>
            <a href="#" class="footer__social-link" aria-label="TikTok" target="_blank" rel="noopener">
              <i class="ti ti-brand-tiktok"></i>
            </a>
          </div>
        </div>

        <!-- Column 2: Navigasi Cepat -->
        <div>
          <h4 class="footer__heading">Navigasi Cepat</h4>
          <div class="footer__links">
            <a href="/" class="footer__link">
              <i class="ti ti-chevron-right"></i>
              Beranda
            </a>
            <a href="/profil/tentang" class="footer__link">
              <i class="ti ti-chevron-right"></i>
              Tentang Kami
            </a>
            <a href="/profil/visi-misi" class="footer__link">
              <i class="ti ti-chevron-right"></i>
              Visi &amp; Misi
            </a>
            <a href="/profil/guru" class="footer__link">
              <i class="ti ti-chevron-right"></i>
              Guru &amp; Pendidik
            </a>
            <a href="/berita" class="footer__link">
              <i class="ti ti-chevron-right"></i>
              Berita
            </a>
            <a href="/galeri" class="footer__link">
              <i class="ti ti-chevron-right"></i>
              Galeri
            </a>
            <a href="/kontak" class="footer__link">
              <i class="ti ti-chevron-right"></i>
              Kontak
            </a>
          </div>
        </div>

        <!-- Column 3: Program -->
        <div>
          <h4 class="footer__heading">Program Kami</h4>
          <div class="footer__links">
            <a href="/akademik/kurikulum" class="footer__link">
              <i class="ti ti-chevron-right"></i>
              Kurikulum
            </a>
            <a href="/akademik/program-unggulan" class="footer__link">
              <i class="ti ti-chevron-right"></i>
              Program Unggulan
            </a>
            <a href="/ppdb" class="footer__link">
              <i class="ti ti-chevron-right"></i>
              Pendaftaran (PPDB)
            </a>
            <a href="/profil/fasilitas" class="footer__link">
              <i class="ti ti-chevron-right"></i>
              Fasilitas
            </a>
          </div>
        </div>

        <!-- Column 4: Kontak -->
        <div>
          <h4 class="footer__heading">Hubungi Kami</h4>

          <div class="footer__contact-item">
            <i class="ti ti-map-pin footer__contact-icon"></i>
            <div class="footer__contact-text">
              Jl. Contoh Alamat No. 123,<br>
              Kelurahan, Kecamatan,<br>
              Kota, Provinsi 12345
            </div>
          </div>

          <div class="footer__contact-item">
            <i class="ti ti-phone footer__contact-icon"></i>
            <div class="footer__contact-text">
              <a href="tel:+6281234567890">+62 812-3456-7890</a>
            </div>
          </div>

          <div class="footer__contact-item">
            <i class="ti ti-mail footer__contact-icon"></i>
            <div class="footer__contact-text">
              <a href="mailto:info@raattakalyaqiin.sch.id">info@raattakalyaqiin.sch.id</a>
            </div>
          </div>

          <div class="footer__contact-item">
            <i class="ti ti-clock footer__contact-icon"></i>
            <div class="footer__contact-text">
              Senin – Jumat: 07.00 – 12.00 WIB<br>
              Sabtu: 07.00 – 10.00 WIB
            </div>
          </div>

          <!-- Mini map -->
          <div class="footer__map">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0!2d106.8!3d-6.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDgnMDAuMCJF!5e0!3m2!1sid!2sid!4v1"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              title="Lokasi RA Attakal Yaqiin"
            ></iframe>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Bottom Bar -->
  <div class="footer__bottom">
    <div class="container">
      <div class="footer__bottom-inner">
        <p class="footer__copyright">
          &copy; <?= date('Y') ?> RA Attakal Yaqiin. Seluruh hak cipta dilindungi.
        </p>
        <p class="footer__developer">
          Dibuat dengan <span style="color:var(--primary);">❤</span> oleh
          <a href="#" target="_blank" rel="noopener">Tim Pengembang</a>
        </p>
      </div>
    </div>
  </div>

</footer>

<!-- ======================== BACK TO TOP ======================== -->
<button class="back-to-top" type="button" aria-label="Kembali ke atas">
  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"/><path d="M5 12l7-7 7 7"/></svg>
</button>
