<?php
/**
 * seo-head.php — Dynamic SEO Meta Tags
 * RA Attakal Yaqiin
 *
 * Expected variables:
 *   $pageTitle       – Page title (string)
 *   $pageDescription – Meta description (string)
 *   $pageKeywords    – Meta keywords (string)
 *   $pageUrl         – Canonical URL (string)
 *   $ogImage         – Open Graph image URL (string)
 */

// Defaults
$siteName        = 'RA Attakal Yaqiin';
$siteTagline     = 'Raudhatul Athfal Attakal Yaqiin';
$defaultDesc     = 'RA Attakal Yaqiin — Taman Kanak-Kanak Islam yang mengedepankan pendidikan karakter Islami, akhlak mulia, dan kecerdasan anak usia dini.';
$defaultKeywords = 'RA Attakal Yaqiin, TK Islam, pendidikan anak usia dini, PAUD Islam, Raudhatul Athfal, sekolah Islam, pendidikan karakter';
$defaultImage    = '/assets/images/og-image.jpg';
$baseUrl         = rtrim($_SERVER['REQUEST_SCHEME'] ?? 'https', '/') . '://' . ($_SERVER['HTTP_HOST'] ?? 'raattakalyaqiin.sch.id');

$title       = isset($pageTitle) && $pageTitle ? $pageTitle . ' | ' . $siteName : $siteName . ' — ' . $siteTagline;
$description = isset($pageDescription) && $pageDescription ? $pageDescription : $defaultDesc;
$keywords    = isset($pageKeywords) && $pageKeywords ? $pageKeywords : $defaultKeywords;
$canonical   = isset($pageUrl) && $pageUrl ? $pageUrl : $baseUrl . ($_SERVER['REQUEST_URI'] ?? '/');
$ogImg       = isset($ogImage) && $ogImage ? $ogImage : $baseUrl . $defaultImage;
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title><?= htmlspecialchars($title) ?></title>
<meta name="description" content="<?= htmlspecialchars($description) ?>">
<meta name="keywords" content="<?= htmlspecialchars($keywords) ?>">
<meta name="author" content="<?= $siteName ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= $siteName ?>">
<meta property="og:title" content="<?= htmlspecialchars($title) ?>">
<meta property="og:description" content="<?= htmlspecialchars($description) ?>">
<meta property="og:image" content="<?= htmlspecialchars($ogImg) ?>">
<meta property="og:url" content="<?= htmlspecialchars($canonical) ?>">
<meta property="og:locale" content="id_ID">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($title) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($description) ?>">
<meta name="twitter:image" content="<?= htmlspecialchars($ogImg) ?>">

<!-- Favicons -->
<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">
<meta name="theme-color" content="#E8600A">

<!-- Preconnect -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

<!-- JSON-LD: EducationalOrganization -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "EducationalOrganization",
  "name": "Raudhatul Athfal (RA) Attakal Yaqiin",
  "alternateName": "RA Attakal Yaqiin",
  "url": "<?= htmlspecialchars($baseUrl) ?>",
  "logo": "<?= htmlspecialchars($baseUrl) ?>/assets/images/logo.png",
  "image": "<?= htmlspecialchars($ogImg) ?>",
  "description": "<?= htmlspecialchars($defaultDesc) ?>",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "",
    "addressRegion": "",
    "addressCountry": "ID"
  },
  "telephone": "",
  "email": "",
  "sameAs": [],
  "foundingDate": "",
  "educationalCredentialAwarded": "Pendidikan Anak Usia Dini",
  "isicV4": "8510"
}
</script>
