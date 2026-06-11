<?php
/**
 * GalleryController
 * RA Attakal Yaqiin - Website Sekolah
 */

declare(strict_types=1);

class GalleryController
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Halaman Galeri Foto
     */
    public function index(): void
    {
        $galleryModel = new Gallery();

        // Get filter album if exists
        $selectedAlbum = isset($_GET['album']) ? trim($_GET['album']) : null;
        if ($selectedAlbum === '') $selectedAlbum = null;

        // Fetch gallery items & albums list
        $items = $galleryModel->getAll($selectedAlbum);
        $albums = $galleryModel->getAlbums();

        // SEO Meta
        $pageTitle = 'Galeri Kegiatan';
        if ($selectedAlbum !== null) {
            $pageTitle = 'Album: ' . $selectedAlbum . ' - Galeri Kegiatan';
        }
        $pageDescription = 'Galeri foto dokumentasi berbagai kegiatan, sarana prasarana, dan prestasi siswa RA Attakal Yaqiin.';
        $pageKeywords = 'galeri foto, dokumentasi kegiatan, foto sekolah, tk islam';
        $currentPage = 'galeri';

        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Galeri', 'url' => $selectedAlbum !== null ? '/galeri' : null]
        ];

        if ($selectedAlbum !== null) {
            $breadcrumbs[] = ['label' => $selectedAlbum, 'url' => null];
        }

        ob_start();
        require BASE_PATH . '/views/gallery/index.php';
        $content = ob_get_clean();

        require BASE_PATH . '/views/layouts/main.php';
    }
}
