<?php
/**
 * HomeController
 * RA Attakal Yaqiin - Website Sekolah
 */

declare(strict_types=1);

class HomeController
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Tampilan Halaman Utama (Homepage)
     */
    public function index(): void
    {
        // Instansiasi model
        $settingModel = new Setting();
        $newsModel = new News();
        $teacherModel = new Teacher();
        $galleryModel = new Gallery();

        // Mengambil pengaturan SEO
        $settings = $settingModel->getMultiple([
            'site_name',
            'site_tagline',
            'site_description',
            'site_keywords'
        ]);

        // Mengambil data untuk homepage
        $latestNews = $newsModel->getLatest(3);
        $teachers = $teacherModel->getAll(true);
        $latestGallery = $galleryModel->getLatest(6);

        // SEO Meta
        $pageTitle = 'Beranda';
        $pageDescription = $settings['site_description'] ?? 'Selamat datang di RA Attakal Yaqiin';
        $pageKeywords = $settings['site_keywords'] ?? 'RA Attakal Yaqiin, TK Islam, Raudhatul Athfal';
        $currentPage = 'beranda';

        // Breadcrumbs untuk beranda (biasanya kosong atau hanya Beranda)
        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => null]
        ];

        // Output buffering
        ob_start();
        require BASE_PATH . '/views/home/index.php';
        $content = ob_get_clean();

        // Render layout utama
        require BASE_PATH . '/views/layouts/main.php';
    }
}
