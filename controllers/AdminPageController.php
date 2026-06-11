<?php
/**
 * Controller AdminPage
 * RA Attakal Yaqiin
 */

declare(strict_types=1);

class AdminPageController
{
    private Page $pageModel;

    public function __construct()
    {
        require_auth();
        $this->pageModel = new Page();
    }

    /**
     * Tampilkan daftar halaman statis
     */
    public function index(): void
    {
        $title = 'Kelola Halaman';
        
        // Ambil semua halaman
        $pages = $this->pageModel->getAllAdmin();

        ob_start();
        require VIEWS_PATH . '/admin/pages/index.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Tampilkan form edit halaman
     */
    public function edit(string $idStr): void
    {
        $id = (int) $idStr;
        $page = $this->pageModel->getById($id);

        if (!$page) {
            flash('error', 'Halaman tidak ditemukan.');
            redirect('/admin/pages');
        }

        $title = 'Edit Halaman: ' . $page['title'];

        ob_start();
        require VIEWS_PATH . '/admin/pages/edit.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Proses simpan perubahan halaman
     */
    public function update(string $idStr): void
    {
        $id = (int) $idStr;
        $page = $this->pageModel->getById($id);

        if (!$page) {
            flash('error', 'Halaman tidak ditemukan.');
            redirect('/admin/pages');
        }

        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid. Silakan coba lagi.');
            redirect('/admin/pages/edit/' . $id);
        }

        $titleInput = trim((string) input('title', ''));
        $contentInput = trim((string) input('content', ''));
        $status = input('status', 'draft');
        $metaTitle = trim((string) input('meta_title', ''));
        $metaDesc = trim((string) input('meta_description', ''));
        $metaKeywords = trim((string) input('meta_keywords', ''));

        // Server-side validation
        if (empty($titleInput)) {
            flash('error', 'Judul halaman wajib diisi.');
            redirect('/admin/pages/edit/' . $id);
        }
        if (empty($contentInput)) {
            flash('error', 'Konten halaman wajib diisi.');
            redirect('/admin/pages/edit/' . $id);
        }

        $data = [
            'title' => $titleInput,
            'content' => $contentInput,
            'status' => $status,
            'meta_title' => $metaTitle !== '' ? $metaTitle : $titleInput,
            'meta_description' => $metaDesc !== '' ? $metaDesc : truncate($contentInput, 160),
            'meta_keywords' => $metaKeywords,
            'regenerate_slug' => ($page['title'] !== $titleInput), // Hanya ganti slug jika judul diubah
        ];

        try {
            $this->pageModel->update($id, $data);
            flash('success', 'Halaman berhasil diperbarui.');
            redirect('/admin/pages');
        } catch (\Exception $e) {
            flash('error', 'Gagal memperbarui halaman: ' . $e->getMessage());
            redirect('/admin/pages/edit/' . $id);
        }
    }
}
