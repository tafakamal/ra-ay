<?php
/**
 * NewsController
 * RA Attakal Yaqiin - Website Sekolah
 */

declare(strict_types=1);

class NewsController
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Halaman Utama Berita (Daftar Semua Berita)
     */
    public function index(): void
    {
        $newsModel = new News();
        $categoryModel = new Category();

        // Get current page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $perPage = 6;
        $newsData = $newsModel->getAll($page, $perPage);
        $categories = $categoryModel->getWithCount();
        $latestNews = $newsModel->getLatest(4); // For sidebar

        // SEO Meta
        $pageTitle = 'Berita & Artikel';
        $pageDescription = 'Ikuti berita terbaru, dokumentasi kegiatan, dan artikel parenting dari RA Attakal Yaqiin.';
        $pageKeywords = 'berita tk islam, kegiatan anak, artikel parenting islami';
        $currentPage = 'berita';

        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Berita', 'url' => null]
        ];

        ob_start();
        require BASE_PATH . '/views/news/index.php';
        $content = ob_get_clean();

        require BASE_PATH . '/views/layouts/main.php';
    }

    /**
     * Pencarian Berita
     */
    public function search(): void
    {
        $newsModel = new News();
        $categoryModel = new Category();

        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $perPage = 6;
        
        // If keyword is empty, redirect to news page
        if ($keyword === '') {
            header('Location: /berita');
            exit;
        }

        $newsData = $newsModel->search($keyword, $page, $perPage);
        $categories = $categoryModel->getWithCount();
        $latestNews = $newsModel->getLatest(4); // For sidebar

        // SEO Meta
        $pageTitle = 'Hasil Pencarian: "' . $keyword . '"';
        $pageDescription = 'Hasil pencarian berita untuk kata kunci: ' . $keyword;
        $pageKeywords = 'cari berita, ' . $keyword;
        $currentPage = 'berita';

        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Berita', 'url' => '/berita'],
            ['label' => 'Pencarian', 'url' => null]
        ];

        ob_start();
        require BASE_PATH . '/views/news/index.php'; // Reuse index view for search results
        $content = ob_get_clean();

        require BASE_PATH . '/views/layouts/main.php';
    }

    /**
     * Daftar Berita Berdasarkan Kategori
     */
    public function category(string $slug): void
    {
        $newsModel = new News();
        $categoryModel = new Category();

        // Check if category exists
        $category = $categoryModel->getBySlug($slug);
        if (!$category) {
            header('Location: /berita');
            exit;
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $perPage = 6;
        $newsData = $newsModel->getByCategory($slug, $page, $perPage);
        $categories = $categoryModel->getWithCount();
        $latestNews = $newsModel->getLatest(4); // For sidebar

        // SEO Meta
        $pageTitle = 'Kategori: ' . $category['name'];
        $pageDescription = $category['description'] ?? 'Daftar berita dalam kategori ' . $category['name'];
        $pageKeywords = 'kategori berita, ' . $category['name'];
        $currentPage = 'berita';

        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Berita', 'url' => '/berita'],
            ['label' => 'Kategori', 'url' => null],
            ['label' => $category['name'], 'url' => null]
        ];

        ob_start();
        require BASE_PATH . '/views/news/category.php';
        $content = ob_get_clean();

        require BASE_PATH . '/views/layouts/main.php';
    }

    /**
     * Halaman Detail Berita
     */
    public function detail(string $slug): void
    {
        $newsModel = new News();

        $article = $newsModel->getBySlug($slug);
        if (!$article) {
            // Redirect or trigger 404
            header('Location: /berita');
            exit;
        }

        // Increment views count
        $newsModel->incrementViews((int)$article['id']);

        $categoryModel = new Category();
        $categories = $categoryModel->getWithCount();
        $latestNews = $newsModel->getLatest(4); // For sidebar
        $relatedNews = $newsModel->getRelated((int)$article['id'], 3); // Related posts

        // SEO Meta
        $pageTitle = $article['meta_title'] ?? $article['title'];
        $pageDescription = $article['meta_description'] ?? ($article['excerpt'] ?? truncate(strip_tags($article['content']), 150));
        $pageKeywords = $article['meta_keywords'] ?? 'berita sekolah, ' . $article['title'];
        $currentPage = 'berita';

        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Berita', 'url' => '/berita'],
            ['label' => $article['title'], 'url' => null]
        ];

        ob_start();
        require BASE_PATH . '/views/news/detail.php';
        $content = ob_get_clean();

        require BASE_PATH . '/views/layouts/main.php';
    }
}
