<?php
/**
 * Controller AdminCategory
 * RA Attakal Yaqiin
 */

declare(strict_types=1);

class AdminCategoryController
{
    private Category $categoryModel;

    public function __construct()
    {
        require_auth();
        $this->categoryModel = new Category();
    }

    /**
     * Tampilkan daftar kategori berita
     */
    public function index(): void
    {
        $title = 'Kategori Berita';
        
        // Ambil semua kategori (termasuk yang nonaktif)
        $categories = $this->categoryModel->getAllAdmin();

        ob_start();
        require VIEWS_PATH . '/admin/categories/index.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Tampilkan form tambah kategori baru
     */
    public function create(): void
    {
        $title = 'Tambah Kategori';

        ob_start();
        require VIEWS_PATH . '/admin/categories/create.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Proses simpan kategori baru
     */
    public function store(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid. Silakan coba lagi.');
            redirect('/admin/categories/create');
        }

        $name = trim((string) input('name', ''));
        $color = trim((string) input('color', '#E8600A'));
        $sortOrder = (int) input('sort_order', 0);
        $isActive = (int) input('is_active', 1);

        // Server-side validation
        if (empty($name)) {
            flash('error', 'Nama kategori wajib diisi.');
            redirect('/admin/categories/create');
        }

        $data = [
            'name' => $name,
            'color' => $color,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ];

        try {
            $this->categoryModel->create($data);
            flash('success', 'Kategori berhasil ditambahkan.');
            redirect('/admin/categories');
        } catch (\Exception $e) {
            flash('error', 'Gagal menambahkan kategori: ' . $e->getMessage());
            redirect('/admin/categories/create');
        }
    }

    /**
     * Tampilkan form edit kategori
     */
    public function edit(string $idStr): void
    {
        $id = (int) $idStr;
        $category = $this->categoryModel->getById($id);

        if (!$category) {
            flash('error', 'Kategori tidak ditemukan.');
            redirect('/admin/categories');
        }

        $title = 'Edit Kategori: ' . $category['name'];

        ob_start();
        require VIEWS_PATH . '/admin/categories/edit.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Proses simpan perubahan kategori
     */
    public function update(string $idStr): void
    {
        $id = (int) $idStr;
        $category = $this->categoryModel->getById($id);

        if (!$category) {
            flash('error', 'Kategori tidak ditemukan.');
            redirect('/admin/categories');
        }

        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid. Silakan coba lagi.');
            redirect('/admin/categories/edit/' . $id);
        }

        $name = trim((string) input('name', ''));
        $color = trim((string) input('color', '#E8600A'));
        $sortOrder = (int) input('sort_order', 0);
        $isActive = (int) input('is_active', 1);

        // Server-side validation
        if (empty($name)) {
            flash('error', 'Nama kategori wajib diisi.');
            redirect('/admin/categories/edit/' . $id);
        }

        // Generate slug dari nama baru jika nama diubah
        $data = [
            'name' => $name,
            'color' => $color,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ];

        if ($category['name'] !== $name) {
            $data['slug'] = slugify($name);
        }

        try {
            $this->categoryModel->update($id, $data);
            flash('success', 'Kategori berhasil diperbarui.');
            redirect('/admin/categories');
        } catch (\Exception $e) {
            flash('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
            redirect('/admin/categories/edit/' . $id);
        }
    }

    /**
     * Proses hapus kategori
     */
    public function delete(string $idStr): void
    {
        $id = (int) $idStr;
        $category = $this->categoryModel->getById($id);

        if (!$category) {
            flash('error', 'Kategori tidak ditemukan.');
            redirect('/admin/categories');
        }

        try {
            $this->categoryModel->delete($id);
            flash('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            flash('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }

        redirect('/admin/categories');
    }
}
