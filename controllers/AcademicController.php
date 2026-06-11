<?php
/**
 * AcademicController
 * RA Attakal Yaqiin - Website Sekolah
 */

declare(strict_types=1);

class AcademicController
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Halaman Induk Akademik (Redirect ke Kurikulum)
     */
    public function index(): void
    {
        header('Location: /akademik/kurikulum');
        exit;
    }

    /**
     * Halaman Kurikulum Sekolah
     */
    public function curriculum(): void
    {
        $pageModel = new Page();
        $page = $pageModel->getBySlug('kurikulum');

        if (!$page) {
            $page = [
                'title' => 'Kurikulum',
                'content' => '<p>Informasi kurikulum sedang dalam pemeliharaan.</p>',
                'meta_title' => 'Kurikulum - RA Attakal Yaqiin',
                'meta_description' => 'Struktur kurikulum terpadu di RA Attakal Yaqiin.'
            ];
        }

        $pageTitle = $page['title'];
        $pageDescription = $page['meta_description'] ?? 'Struktur kurikulum terpadu di RA Attakal Yaqiin.';
        $pageKeywords = 'kurikulum merdeka, kurikulum kemenag, pelajaran tk islam, materi belajar PAUD';
        $currentPage = 'akademik';

        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Akademik', 'url' => null],
            ['label' => 'Kurikulum', 'url' => null]
        ];

        ob_start();
        require BASE_PATH . '/views/academic/curriculum.php';
        $content = ob_get_clean();

        require BASE_PATH . '/views/layouts/main.php';
    }

    /**
     * Halaman Program Unggulan
     */
    public function programs(): void
    {
        $pageModel = new Page();
        $page = $pageModel->getBySlug('program-unggulan');

        if (!$page) {
            $page = [
                'title' => 'Program Unggulan',
                'content' => '<p>Informasi program unggulan sekolah sedang dalam pemeliharaan.</p>',
                'meta_title' => 'Program Unggulan - RA Attakal Yaqiin',
                'meta_description' => 'Daftar program keagamaan dan tumbuh kembang unggulan di RA Attakal Yaqiin.'
            ];
        }

        $pageTitle = $page['title'];
        $pageDescription = $page['meta_description'] ?? 'Daftar program keagamaan dan tumbuh kembang unggulan di RA Attakal Yaqiin.';
        $pageKeywords = 'program unggulan, tahfidz cilik, manasik haji cilik, belajar bahasa arab anak';
        $currentPage = 'akademik';

        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Akademik', 'url' => '/akademik/kurikulum'],
            ['label' => 'Program Unggulan', 'url' => null]
        ];

        ob_start();
        require BASE_PATH . '/views/academic/programs.php';
        $content = ob_get_clean();

        require BASE_PATH . '/views/layouts/main.php';
    }
}
