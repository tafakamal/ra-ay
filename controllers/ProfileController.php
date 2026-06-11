<?php
/**
 * ProfileController
 * RA Attakal Yaqiin - Website Sekolah
 */

declare(strict_types=1);

class ProfileController
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Halaman Tentang Kami / Profil Sekolah
     */
    public function about(): void
    {
        $pageModel = new Page();
        // Slug di database adalah 'tentang-sekolah'
        $page = $pageModel->getBySlug('tentang-sekolah');

        if (!$page) {
            // Fallback jika data kosong
            $page = [
                'title' => 'Tentang Kami',
                'content' => '<p>Informasi tentang sekolah sedang dalam pemeliharaan.</p>',
                'meta_title' => 'Tentang Kami - RA Attakal Yaqiin',
                'meta_description' => 'Profil dan sejarah singkat RA Attakal Yaqiin.'
            ];
        }

        $pageTitle = $page['title'];
        $pageDescription = $page['meta_description'] ?? 'Profil dan sejarah singkat RA Attakal Yaqiin.';
        $pageKeywords = 'profil RA Attakal Yaqiin, tentang kami, tk islam ciputat';
        $currentPage = 'profil';

        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Profil', 'url' => null],
            ['label' => 'Tentang Kami', 'url' => null]
        ];

        ob_start();
        require BASE_PATH . '/views/profile/about.php';
        $content = ob_get_clean();

        require BASE_PATH . '/views/layouts/main.php';
    }

    /**
     * Halaman Visi & Misi
     */
    public function visionMission(): void
    {
        $pageModel = new Page();
        $page = $pageModel->getBySlug('visi-misi');

        if (!$page) {
            $page = [
                'title' => 'Visi & Misi',
                'content' => '<p>Informasi visi dan misi sedang dalam pemeliharaan.</p>',
                'meta_title' => 'Visi & Misi - RA Attakal Yaqiin',
                'meta_description' => 'Visi, misi, dan tujuan pendidikan RA Attakal Yaqiin.'
            ];
        }

        $pageTitle = $page['title'];
        $pageDescription = $page['meta_description'] ?? 'Visi, misi, dan tujuan pendidikan RA Attakal Yaqiin.';
        $pageKeywords = 'visi misi, tujuan sekolah, target kelulusan RA Attakal Yaqiin';
        $currentPage = 'profil';

        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Profil', 'url' => '/profil/tentang'],
            ['label' => 'Visi & Misi', 'url' => null]
        ];

        ob_start();
        require BASE_PATH . '/views/profile/vision-mission.php';
        $content = ob_get_clean();

        require BASE_PATH . '/views/layouts/main.php';
    }

    /**
     * Halaman Guru & Tenaga Pendidik
     */
    public function teachers(): void
    {
        $teacherModel = new Teacher();
        $teachers = $teacherModel->getAll(true);

        $pageTitle = 'Guru & Tenaga Pendidik';
        $pageDescription = 'Daftar guru dan staff pengajar profesional dan berdedikasi di RA Attakal Yaqiin.';
        $pageKeywords = 'guru tk islam, ustadzah RA Attakal Yaqiin, staff pengajar PAUD';
        $currentPage = 'profil';

        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Profil', 'url' => '/profil/tentang'],
            ['label' => 'Guru & Staff', 'url' => null]
        ];

        ob_start();
        require BASE_PATH . '/views/profile/teachers.php';
        $content = ob_get_clean();

        require BASE_PATH . '/views/layouts/main.php';
    }

    /**
     * Halaman Fasilitas Sekolah
     */
    public function facilities(): void
    {
        $pageModel = new Page();
        $page = $pageModel->getBySlug('fasilitas');

        if (!$page) {
            $page = [
                'title' => 'Fasilitas',
                'content' => '<p>Informasi sarana dan prasarana sedang dalam pemeliharaan.</p>',
                'meta_title' => 'Fasilitas - RA Attakal Yaqiin',
                'meta_description' => 'Sarana dan prasarana penunjang kegiatan belajar di RA Attakal Yaqiin.'
            ];
        }

        $pageTitle = $page['title'];
        $pageDescription = $page['meta_description'] ?? 'Sarana dan prasarana penunjang kegiatan belajar di RA Attakal Yaqiin.';
        $pageKeywords = 'fasilitas sekolah, playground, kelas AC, perpustakaan anak';
        $currentPage = 'profil';

        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Profil', 'url' => '/profil/tentang'],
            ['label' => 'Fasilitas', 'url' => null]
        ];

        ob_start();
        require BASE_PATH . '/views/profile/facilities.php';
        $content = ob_get_clean();

        require BASE_PATH . '/views/layouts/main.php';
    }
}
