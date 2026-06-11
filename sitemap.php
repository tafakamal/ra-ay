<?php
/**
 * Sitemap Generator (XML)
 * RA Attakal Yaqiin - Website Sekolah
 *
 * Menghasilkan sitemap XML secara dinamis dari halaman statis
 * dan berita yang sudah dipublikasikan.
 */

declare(strict_types=1);

// Muat konfigurasi aplikasi
require_once __DIR__ . '/config/app.php';

// Autoload model
spl_autoload_register(function (string $className): void {
    $modelFile = BASE_PATH . '/models/' . $className . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
    }
});

// Set header untuk XML
header('Content-Type: application/xml; charset=UTF-8');
header('Cache-Control: public, max-age=3600'); // Cache 1 jam

// Mulai output XML
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

// ============================================================
// 1. Halaman Statis (Static Pages)
// ============================================================

// Beranda
outputUrl(url('/'), date('Y-m-d'), 'daily', '1.0');

// Halaman profil
$staticPages = [
    '/profil'              => ['priority' => '0.8', 'changefreq' => 'monthly'],
    '/profil/visi-misi'    => ['priority' => '0.7', 'changefreq' => 'yearly'],
    '/profil/guru'         => ['priority' => '0.7', 'changefreq' => 'monthly'],
    '/profil/fasilitas'    => ['priority' => '0.7', 'changefreq' => 'monthly'],
    '/akademik'            => ['priority' => '0.7', 'changefreq' => 'monthly'],
    '/akademik/kurikulum'  => ['priority' => '0.7', 'changefreq' => 'yearly'],
    '/akademik/program'    => ['priority' => '0.7', 'changefreq' => 'yearly'],
    '/berita'              => ['priority' => '0.9', 'changefreq' => 'daily'],
    '/galeri'              => ['priority' => '0.6', 'changefreq' => 'weekly'],
    '/kontak'              => ['priority' => '0.5', 'changefreq' => 'yearly'],
    '/ppdb'                => ['priority' => '0.8', 'changefreq' => 'monthly'],
];

foreach ($staticPages as $path => $config) {
    outputUrl(url($path), date('Y-m-d'), $config['changefreq'], $config['priority']);
}

// ============================================================
// 2. Halaman Dinamis dari Database (Pages)
// ============================================================
try {
    $pageModel = new Page();
    $pages = $pageModel->getForSitemap();

    foreach ($pages as $page) {
        $lastmod = !empty($page['updated_at'])
            ? date('Y-m-d', strtotime($page['updated_at']))
            : date('Y-m-d');

        // Halaman statis dari DB biasanya diakses via route profil/akademik
        // Jadi kita skip untuk menghindari duplikat URL
        // (sudah di-cover oleh staticPages di atas)
    }
} catch (\Throwable $e) {
    // Log error tapi jangan hentikan sitemap
    error_log("Sitemap - Error loading pages: " . $e->getMessage());
}

// ============================================================
// 3. Berita yang Sudah Dipublikasi
// ============================================================
try {
    $newsModel = new News();
    $newsList = $newsModel->getForSitemap();

    foreach ($newsList as $news) {
        $lastmod = !empty($news['updated_at'])
            ? date('Y-m-d', strtotime($news['updated_at']))
            : date('Y-m-d', strtotime($news['published_at']));

        outputUrl(
            url('/berita/' . $news['slug']),
            $lastmod,
            'monthly',
            '0.6'
        );
    }
} catch (\Throwable $e) {
    error_log("Sitemap - Error loading news: " . $e->getMessage());
}

// ============================================================
// 4. Halaman Kategori Berita
// ============================================================
try {
    $categoryModel = new Category();
    $categories = $categoryModel->getAll();

    foreach ($categories as $category) {
        outputUrl(
            url('/berita/kategori/' . $category['slug']),
            date('Y-m-d'),
            'weekly',
            '0.5'
        );
    }
} catch (\Throwable $e) {
    error_log("Sitemap - Error loading categories: " . $e->getMessage());
}

echo '</urlset>' . PHP_EOL;

// ============================================================
// Helper Function
// ============================================================

/**
 * Output satu entry URL di sitemap
 *
 * @param string $loc URL lengkap
 * @param string $lastmod Tanggal modifikasi terakhir (format: Y-m-d)
 * @param string $changefreq Frekuensi perubahan
 * @param string $priority Prioritas (0.0 - 1.0)
 */
function outputUrl(string $loc, string $lastmod, string $changefreq, string $priority): void
{
    echo '  <url>' . PHP_EOL;
    echo '    <loc>' . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
    echo '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
    echo '    <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
    echo '    <priority>' . $priority . '</priority>' . PHP_EOL;
    echo '  </url>' . PHP_EOL;
}
