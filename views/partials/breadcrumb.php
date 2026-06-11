<?php
/**
 * breadcrumb.php — Semantic Breadcrumb Navigation
 * RA Attakal Yaqiin
 *
 * Expected variable:
 *   $breadcrumbs — Array of [label => string, url => string|null]
 *   Last item (url = null) is the current page.
 *
 * Example:
 *   $breadcrumbs = [
 *       ['label' => 'Beranda',  'url' => '/'],
 *       ['label' => 'Profil',   'url' => '/profil'],
 *       ['label' => 'Tentang',  'url' => null],
 *   ];
 */

if (!isset($breadcrumbs) || empty($breadcrumbs)) return;
?>

<!-- JSON-LD: BreadcrumbList -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    <?php foreach ($breadcrumbs as $i => $crumb): ?>
    {
      "@type": "ListItem",
      "position": <?= $i + 1 ?>,
      "name": "<?= htmlspecialchars($crumb['label']) ?>"<?php if (!empty($crumb['url'])): ?>,
      "item": "<?= htmlspecialchars((isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? 'raattakalyaqiin.sch.id') . $crumb['url']) ?>"<?php endif; ?>
    }<?= $i < count($breadcrumbs) - 1 ? ',' : '' ?>

    <?php endforeach; ?>
  ]
}
</script>

<nav class="breadcrumb" aria-label="Breadcrumb">
  <div class="container">
    <ol class="breadcrumb__list" itemscope itemtype="https://schema.org/BreadcrumbList">
      <?php foreach ($breadcrumbs as $i => $crumb): ?>
        <li class="breadcrumb__item"
            itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">

          <?php if (!empty($crumb['url'])): ?>
            <a href="<?= htmlspecialchars($crumb['url']) ?>" itemprop="item">
              <?php if ($i === 0): ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" style="vertical-align:-2px;margin-right:2px;">
                  <path d="M5 12l-2 0l9-9l9 9l-2 0"/>
                  <path d="M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                  <path d="M9 21v-6a2 2 0 012-2h2a2 2 0 012 2v6"/>
                </svg>
              <?php endif; ?>
              <span itemprop="name"><?= htmlspecialchars($crumb['label']) ?></span>
            </a>
          <?php else: ?>
            <span itemprop="name"><?= htmlspecialchars($crumb['label']) ?></span>
          <?php endif; ?>

          <meta itemprop="position" content="<?= $i + 1 ?>">
        </li>

        <?php if ($i < count($breadcrumbs) - 1): ?>
          <li class="breadcrumb__separator" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round">
              <path d="M9 6l6 6-6 6"/>
            </svg>
          </li>
        <?php endif; ?>

      <?php endforeach; ?>
    </ol>
  </div>
</nav>
