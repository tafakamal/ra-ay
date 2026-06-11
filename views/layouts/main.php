<?php
/**
 * main.php — Main Layout Template
 * RA Attakal Yaqiin
 *
 * Expected variables:
 *   $content          – Main page content (string, required)
 *   $pageTitle        – Page title (string)
 *   $pageDescription  – Meta description (string)
 *   $pageKeywords     – Meta keywords (string)
 *   $pageUrl          – Canonical URL (string)
 *   $ogImage          – Open Graph image (string)
 *   $bodyClass        – Additional body classes (string)
 *   $currentPage      – Current page identifier for nav highlighting (string)
 *   $pageCss          – Array of additional CSS file paths (array)
 *   $pageJs           – Array of additional JS file paths (array)
 *   $pageInlineCss    – Inline CSS string (string)
 *   $pageInlineJs     – Inline JS string (string)
 */

// Ensure required variables have defaults
$content         = $content ?? '';
$bodyClass       = $bodyClass ?? '';
$currentPage     = $currentPage ?? '';
$pageCss         = $pageCss ?? [];
$pageJs          = $pageJs ?? [];
$pageInlineCss   = $pageInlineCss ?? '';
$pageInlineJs    = $pageInlineJs ?? '';
?>
<!DOCTYPE html>
<html lang="id" class="light" data-theme="auto">
<head>
  <?php require_once __DIR__ . '/../partials/seo-head.php'; ?>

  <!-- Google Fonts: Plus Jakarta Sans + Amiri -->
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800&display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800&display=swap">

  <!-- Tabler Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

  <!-- Core Stylesheets -->
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/components.css">

  <!-- Page-specific CSS -->
  <?php foreach ($pageCss as $css): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
  <?php endforeach; ?>

  <!-- Inline CSS -->
  <?php if ($pageInlineCss): ?>
    <style><?= $pageInlineCss ?></style>
  <?php endif; ?>

  <!-- Prevent FOUC: Apply dark mode early -->
  <script>
    (function(){
      var t = localStorage.getItem('raay-theme') || 'auto';
      var d = t === 'auto'
        ? (window.matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light')
        : t;
      document.documentElement.className = d;
      document.documentElement.setAttribute('data-theme', t);
    })();
  </script>
</head>
<body class="<?= htmlspecialchars($bodyClass) ?>">

  <!-- ======================== LOADING SCREEN ======================== -->
  <div class="loading-screen" id="loadingScreen" aria-hidden="true">
    <img
      src="/assets/images/logo.png"
      alt="RA Attakal Yaqiin"
      class="loading-screen__logo"
      width="80"
      height="80"
    >
    <p class="loading-screen__text">RA Attakal Yaqiin</p>
    <div class="loading-screen__dots">
      <span class="loading-screen__dot"></span>
      <span class="loading-screen__dot"></span>
      <span class="loading-screen__dot"></span>
    </div>
  </div>

  <!-- ======================== HEADER / NAVBAR ======================== -->
  <?php require_once __DIR__ . '/../partials/header.php'; ?>

  <!-- ======================== MAIN CONTENT ======================== -->
  <main class="main-content" id="mainContent" role="main">
    <?= $content ?>
  </main>

  <!-- ======================== FOOTER ======================== -->
  <?php require_once __DIR__ . '/../partials/footer.php'; ?>

  <!-- ======================== SCRIPTS ======================== -->
  <script src="/assets/js/main.js" defer></script>

  <!-- Page-specific JS -->
  <?php foreach ($pageJs as $js): ?>
    <script src="<?= htmlspecialchars($js) ?>" defer></script>
  <?php endforeach; ?>

  <!-- Inline JS -->
  <?php if ($pageInlineJs): ?>
    <script><?= $pageInlineJs ?></script>
  <?php endif; ?>

</body>
</html>
