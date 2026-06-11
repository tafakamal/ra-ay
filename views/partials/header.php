<?php
/**
 * header.php — Responsive Navigation Header
 * RA Attakal Yaqiin
 *
 * Expected variables (optional):
 *   $currentPage – identifier for active link highlighting (e.g. 'beranda', 'profil', etc.)
 */

$currentPage = $currentPage ?? '';

/**
 * Helper: returns 'active' if the given page matches the current page
 */
function navActive(string $page, string $current): string {
    return $page === $current ? 'active' : '';
}
?>

<!-- ======================== NAVBAR ======================== -->
<nav class="navbar" id="navbar" role="navigation" aria-label="Navigasi utama">
  <div class="navbar__inner">

    <!-- Brand -->
    <a href="/" class="navbar__brand" aria-label="RA Attakal Yaqiin - Beranda">
      <img
        src="/assets/images/logo.png"
        alt="Logo RA Attakal Yaqiin"
        class="navbar__logo"
        width="44"
        height="44"
        loading="eager"
      >
      <div class="navbar__brand-text">
        <span class="navbar__brand-title">RA Attakal Yaqiin</span>
        <span class="navbar__brand-sub">Raudhatul Athfal</span>
      </div>
    </a>

    <!-- Desktop Menu -->
    <div class="navbar__menu">

      <a href="/" class="navbar__link <?= navActive('beranda', $currentPage) ?>">
        Beranda
      </a>

      <!-- Profil Dropdown -->
      <div class="navbar__dropdown">
        <button class="navbar__link <?= navActive('profil', $currentPage) ?>" aria-expanded="false" aria-haspopup="true">
          Profil
          <svg class="chevron-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div class="navbar__dropdown-menu" role="menu">
          <a href="/profil/tentang" class="navbar__dropdown-link" role="menuitem">
            <i class="ti ti-info-circle icon"></i>
            Tentang Kami
          </a>
          <a href="/profil/visi-misi" class="navbar__dropdown-link" role="menuitem">
            <i class="ti ti-target icon"></i>
            Visi &amp; Misi
          </a>
          <a href="/profil/guru" class="navbar__dropdown-link" role="menuitem">
            <i class="ti ti-users icon"></i>
            Guru &amp; Tenaga Pendidik
          </a>
          <a href="/profil/fasilitas" class="navbar__dropdown-link" role="menuitem">
            <i class="ti ti-building icon"></i>
            Fasilitas
          </a>
        </div>
      </div>

      <!-- Akademik Dropdown -->
      <div class="navbar__dropdown">
        <button class="navbar__link <?= navActive('akademik', $currentPage) ?>" aria-expanded="false" aria-haspopup="true">
          Akademik
          <svg class="chevron-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div class="navbar__dropdown-menu" role="menu">
          <a href="/akademik/kurikulum" class="navbar__dropdown-link" role="menuitem">
            <i class="ti ti-book icon"></i>
            Kurikulum
          </a>
          <a href="/akademik/program-unggulan" class="navbar__dropdown-link" role="menuitem">
            <i class="ti ti-star icon"></i>
            Program Unggulan
          </a>
        </div>
      </div>

      <a href="/berita" class="navbar__link <?= navActive('berita', $currentPage) ?>">
        Berita
      </a>

      <a href="/galeri" class="navbar__link <?= navActive('galeri', $currentPage) ?>">
        Galeri
      </a>

      <a href="/ppdb" class="navbar__link <?= navActive('ppdb', $currentPage) ?>">
        <span style="display:inline-flex;align-items:center;gap:4px;">
          PPDB
          <span class="badge badge-primary" style="font-size:10px;padding:1px 6px;">Baru</span>
        </span>
      </a>

      <a href="/kontak" class="navbar__link <?= navActive('kontak', $currentPage) ?>">
        Kontak
      </a>
    </div>

    <!-- Actions -->
    <div class="navbar__actions">

      <!-- Theme toggle -->
      <button class="theme-toggle" type="button" aria-label="Beralih mode gelap">
        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        <svg class="icon-auto" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 3a9 9 0 0 1 0 18"/><path d="M12 7v10"/></svg>
      </button>

      <!-- Hamburger (mobile) -->
      <button class="navbar__toggle" type="button" aria-label="Menu navigasi" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>

  </div>
</nav>

<!-- Mobile overlay -->
<div class="navbar__overlay" aria-hidden="true"></div>

<!-- ======================== MOBILE MENU ======================== -->
<div class="navbar__mobile" id="mobileMenu" aria-label="Menu navigasi mobile">

  <a href="/" class="navbar__mobile-link <?= navActive('beranda', $currentPage) ?>">
    <i class="ti ti-home"></i>
    Beranda
  </a>

  <!-- Profil dropdown -->
  <div class="navbar__mobile-dropdown">
    <button class="navbar__mobile-dropdown-toggle" type="button">
      <span style="display:flex;align-items:center;gap:8px;">
        <i class="ti ti-school"></i>
        Profil
      </span>
      <svg class="chevron-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
    </button>
    <div class="navbar__mobile-submenu">
      <a href="/profil/tentang">
        <i class="ti ti-info-circle"></i>
        Tentang Kami
      </a>
      <a href="/profil/visi-misi">
        <i class="ti ti-target"></i>
        Visi &amp; Misi
      </a>
      <a href="/profil/guru">
        <i class="ti ti-users"></i>
        Guru &amp; Tenaga Pendidik
      </a>
      <a href="/profil/fasilitas">
        <i class="ti ti-building"></i>
        Fasilitas
      </a>
    </div>
  </div>

  <!-- Akademik dropdown -->
  <div class="navbar__mobile-dropdown">
    <button class="navbar__mobile-dropdown-toggle" type="button">
      <span style="display:flex;align-items:center;gap:8px;">
        <i class="ti ti-book-2"></i>
        Akademik
      </span>
      <svg class="chevron-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
    </button>
    <div class="navbar__mobile-submenu">
      <a href="/akademik/kurikulum">
        <i class="ti ti-book"></i>
        Kurikulum
      </a>
      <a href="/akademik/program-unggulan">
        <i class="ti ti-star"></i>
        Program Unggulan
      </a>
    </div>
  </div>

  <a href="/berita" class="navbar__mobile-link <?= navActive('berita', $currentPage) ?>">
    <i class="ti ti-news"></i>
    Berita
  </a>

  <a href="/galeri" class="navbar__mobile-link <?= navActive('galeri', $currentPage) ?>">
    <i class="ti ti-photo"></i>
    Galeri
  </a>

  <a href="/ppdb" class="navbar__mobile-link <?= navActive('ppdb', $currentPage) ?>">
    <i class="ti ti-clipboard-list"></i>
    PPDB
    <span class="badge badge-primary" style="font-size:10px;">Baru</span>
  </a>

  <a href="/kontak" class="navbar__mobile-link <?= navActive('kontak', $currentPage) ?>">
    <i class="ti ti-mail"></i>
    Kontak
  </a>

  <!-- Mobile CTA -->
  <div class="navbar__mobile-cta">
    <a href="https://wa.me/6281234567890?text=Assalamualaikum%2C%20saya%20ingin%20bertanya%20tentang%20RA%20Attakal%20Yaqiin" class="btn btn-whatsapp w-full" target="_blank" rel="noopener">
      <i class="ti ti-brand-whatsapp"></i>
      Hubungi via WhatsApp
    </a>
  </div>
</div>

<!-- ======================== WHATSAPP FLOATING BUTTON ======================== -->
<a
  href="https://wa.me/6281234567890?text=Assalamualaikum%2C%20saya%20ingin%20bertanya%20tentang%20RA%20Attakal%20Yaqiin"
  class="whatsapp-float"
  target="_blank"
  rel="noopener"
  aria-label="Chat WhatsApp"
>
  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
  <span class="whatsapp-float__text">Chat Kami</span>
</a>
