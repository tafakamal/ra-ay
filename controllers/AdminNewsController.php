<?php
/**
 * Controller AdminNews
 * RA Attakal Yaqiin
 */

declare(strict_types=1);

class AdminNewsController
{
    private Database $db;
    private News $newsModel;

    public function __construct()
    {
        require_auth();
        $this->db = Database::getInstance();
        $this->newsModel = new News();
    }

    /**
     * Tampilkan daftar berita (dengan pencarian dan filter kategori)
     */
    public function index(): void
    {
        $title = 'Kelola Berita';

        $page = (int) input('page', 1);
        if ($page < 1) {
            $page = 1;
        }
        $perPage = ADMIN_PER_PAGE;
        $offset = ($page - 1) * $perPage;

        $search = trim((string) input('q', ''));
        $categoryId = input('category_id', '');

        $where = ["1=1"];
        $params = [];

        if ($search !== '') {
            $where[] = "(n.title LIKE ? OR n.content LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        if ($categoryId !== '') {
            $where[] = "n.category_id = ?";
            $params[] = (int) $categoryId;
        }

        $whereClause = implode(" AND ", $where);

        // Hitung total berita
        $countSql = "SELECT COUNT(*) FROM news n WHERE {$whereClause}";
        $total = (int) $this->db->fetchColumn($countSql, $params);
        $totalPages = (int) ceil($total / $perPage);

        // Ambil data berita
        $sql = "SELECT n.*, c.name AS category_name, u.name AS author_name
                FROM news n
                LEFT JOIN categories c ON n.category_id = c.id
                LEFT JOIN users u ON n.user_id = u.id
                WHERE {$whereClause}
                ORDER BY n.created_at DESC
                LIMIT ? OFFSET ?";

        $queryParams = array_merge($params, [$perPage, $offset]);
        $newsList = $this->db->fetchAll($sql, $queryParams);

        // Fetch categories untuk filter dropdown
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        // Render view index berita
        ob_start();
        require VIEWS_PATH . '/admin/news/index.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Tampilkan form buat berita baru
     */
    public function create(): void
    {
        $title = 'Tambah Berita Baru';

        // Fetch categories untuk dropdown
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        ob_start();
        require VIEWS_PATH . '/admin/news/create.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Proses simpan berita baru
     */
    public function store(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid. Silakan coba lagi.');
            redirect('/admin/news/create');
        }

        $titleInput = trim((string) input('title', ''));
        $contentInput = trim((string) input('content', ''));
        $excerpt = trim((string) input('excerpt', ''));
        $categoryId = input('category_id', '');
        $status = input('status', 'draft');
        $isFeatured = (int) input('is_featured', 0);
        $metaTitle = trim((string) input('meta_title', ''));
        $metaDesc = trim((string) input('meta_description', ''));
        $metaKeywords = trim((string) input('meta_keywords', ''));

        // Server-side validation
        if (empty($titleInput)) {
            flash('error', 'Judul berita wajib diisi.');
            redirect('/admin/news/create');
        }
        if (empty($contentInput)) {
            flash('error', 'Konten berita wajib diisi.');
            redirect('/admin/news/create');
        }

        $featuredImage = '';

        // Handle file upload
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['featured_image']['tmp_name'];
            $fileName = $_FILES['featured_image']['name'];
            $fileSize = $_FILES['featured_image']['size'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                flash('error', 'Ekstensi file gambar tidak diperbolehkan. Hanya JPG, JPEG, PNG, dan WEBP.');
                redirect('/admin/news/create');
            }

            if ($fileSize > UPLOAD_MAX_SIZE) {
                flash('error', 'Ukuran gambar tidak boleh melebihi 5MB.');
                redirect('/admin/news/create');
            }

            $uploadFileDir = BASE_PATH . '/uploads/news/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $destPath = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $featuredImage = 'uploads/news/' . $newFileName;
            } else {
                flash('error', 'Gagal mengunggah gambar.');
                redirect('/admin/news/create');
            }
        }

        // Siapkan data berita
        $data = [
            'title' => $titleInput,
            'excerpt' => $excerpt !== '' ? $excerpt : truncate($contentInput, 150),
            'content' => $contentInput,
            'category_id' => $categoryId !== '' ? (int) $categoryId : null,
            'status' => $status,
            'is_featured' => $isFeatured,
            'user_id' => $_SESSION['user_id'],
            'meta_title' => $metaTitle !== '' ? $metaTitle : $titleInput,
            'meta_description' => $metaDesc !== '' ? $metaDesc : truncate($contentInput, 160),
            'meta_keywords' => $metaKeywords,
        ];

        if ($featuredImage !== '') {
            $data['featured_image'] = $featuredImage;
        }

        try {
            $this->newsModel->create($data);
            flash('success', 'Berita berhasil diterbitkan.');
            redirect('/admin/news');
        } catch (\Exception $e) {
            // Hapus file yang baru diunggah jika db insert gagal
            if ($featuredImage !== '' && file_exists(BASE_PATH . '/' . $featuredImage)) {
                @unlink(BASE_PATH . '/' . $featuredImage);
            }
            flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            redirect('/admin/news/create');
        }
    }

    /**
     * Tampilkan form edit berita
     */
    public function edit(string $idStr): void
    {
        $id = (int) $idStr;
        $news = $this->newsModel->getById($id);

        if (!$news) {
            flash('error', 'Berita tidak ditemukan.');
            redirect('/admin/news');
        }

        $title = 'Edit Berita: ' . $news['title'];

        // Fetch categories untuk dropdown
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        ob_start();
        require VIEWS_PATH . '/admin/news/edit.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Proses simpan perubahan berita
     */
    public function update(string $idStr): void
    {
        $id = (int) $idStr;
        $news = $this->newsModel->getById($id);

        if (!$news) {
            flash('error', 'Berita tidak ditemukan.');
            redirect('/admin/news');
        }

        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid. Silakan coba lagi.');
            redirect('/admin/news/edit/' . $id);
        }

        $titleInput = trim((string) input('title', ''));
        $contentInput = trim((string) input('content', ''));
        $excerpt = trim((string) input('excerpt', ''));
        $categoryId = input('category_id', '');
        $status = input('status', 'draft');
        $isFeatured = (int) input('is_featured', 0);
        $metaTitle = trim((string) input('meta_title', ''));
        $metaDesc = trim((string) input('meta_description', ''));
        $metaKeywords = trim((string) input('meta_keywords', ''));

        // Server-side validation
        if (empty($titleInput)) {
            flash('error', 'Judul berita wajib diisi.');
            redirect('/admin/news/edit/' . $id);
        }
        if (empty($contentInput)) {
            flash('error', 'Konten berita wajib diisi.');
            redirect('/admin/news/edit/' . $id);
        }

        $featuredImage = '';

        // Handle file upload
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['featured_image']['tmp_name'];
            $fileName = $_FILES['featured_image']['name'];
            $fileSize = $_FILES['featured_image']['size'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                flash('error', 'Ekstensi file gambar tidak diperbolehkan. Hanya JPG, JPEG, PNG, dan WEBP.');
                redirect('/admin/news/edit/' . $id);
            }

            if ($fileSize > UPLOAD_MAX_SIZE) {
                flash('error', 'Ukuran gambar tidak boleh melebihi 5MB.');
                redirect('/admin/news/edit/' . $id);
            }

            $uploadFileDir = BASE_PATH . '/uploads/news/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $destPath = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $featuredImage = 'uploads/news/' . $newFileName;

                // Hapus gambar lama jika ada
                if (!empty($news['featured_image'])) {
                    $oldFilePath = BASE_PATH . '/' . $news['featured_image'];
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
                    }
                }
            } else {
                flash('error', 'Gagal mengunggah gambar baru.');
                redirect('/admin/news/edit/' . $id);
            }
        }

        // Siapkan data berita
        $data = [
            'title' => $titleInput,
            'excerpt' => $excerpt !== '' ? $excerpt : truncate($contentInput, 150),
            'content' => $contentInput,
            'category_id' => $categoryId !== '' ? (int) $categoryId : null,
            'status' => $status,
            'is_featured' => $isFeatured,
            'meta_title' => $metaTitle !== '' ? $metaTitle : $titleInput,
            'meta_description' => $metaDesc !== '' ? $metaDesc : truncate($contentInput, 160),
            'meta_keywords' => $metaKeywords,
            'regenerate_slug' => ($news['title'] !== $titleInput), // Hanya regenerasi slug jika judul berubah
        ];

        if ($featuredImage !== '') {
            $data['featured_image'] = $featuredImage;
        }

        try {
            $this->newsModel->update($id, $data);
            flash('success', 'Berita berhasil diperbarui.');
            redirect('/admin/news');
        } catch (\Exception $e) {
            // Hapus file yang baru diunggah jika update gagal
            if ($featuredImage !== '' && file_exists(BASE_PATH . '/' . $featuredImage)) {
                @unlink(BASE_PATH . '/' . $featuredImage);
            }
            flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            redirect('/admin/news/edit/' . $id);
        }
    }

    /**
     * Proses hapus berita
     */
    public function delete(string $idStr): void
    {
        $id = (int) $idStr;
        $news = $this->newsModel->getById($id);

        if (!$news) {
            flash('error', 'Berita tidak ditemukan.');
            redirect('/admin/news');
        }

        try {
            // Hapus file fisik gambar jika ada
            if (!empty($news['featured_image'])) {
                $filePath = BASE_PATH . '/' . $news['featured_image'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            $this->newsModel->delete($id);
            flash('success', 'Berita berhasil dihapus.');
        } catch (\Exception $e) {
            flash('error', 'Gagal menghapus berita: ' . $e->getMessage());
        }

        redirect('/admin/news');
    }
}
