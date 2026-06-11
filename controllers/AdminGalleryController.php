<?php
/**
 * Controller AdminGallery
 * RA Attakal Yaqiin
 */

declare(strict_types=1);

class AdminGalleryController
{
    private Database $db;
    private Gallery $galleryModel;

    public function __construct()
    {
        require_auth();
        $this->db = Database::getInstance();
        $this->galleryModel = new Gallery();
    }

    /**
     * Tampilkan daftar item galeri
     */
    public function index(): void
    {
        $title = 'Galeri Foto & Video';

        $albumFilter = trim((string) input('album', ''));
        
        // Dapatkan semua album untuk filter dropdown
        $albums = $this->galleryModel->getAlbums();

        // Ambil data galeri berdasarkan filter album
        $where = ["1=1"];
        $params = [];

        if ($albumFilter !== '') {
            $where[] = "album = ?";
            $params[] = $albumFilter;
        }

        $whereClause = implode(" AND ", $where);
        $sql = "SELECT * FROM gallery WHERE {$whereClause} ORDER BY sort_order ASC, created_at DESC";
        $galleryItems = $this->db->fetchAll($sql, $params);

        ob_start();
        require VIEWS_PATH . '/admin/gallery/index.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Tampilkan form tambah galeri baru
     */
    public function create(): void
    {
        $title = 'Tambah Galeri Baru';

        // Dapatkan daftar album yang sudah ada untuk saran input autofill
        $albums = $this->galleryModel->getAlbums();

        ob_start();
        require VIEWS_PATH . '/admin/gallery/create.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Proses simpan galeri baru
     */
    public function store(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid. Silakan coba lagi.');
            redirect('/admin/gallery/create');
        }

        $titleInput = trim((string) input('title', ''));
        $description = trim((string) input('description', ''));
        $album = trim((string) input('album', ''));
        $type = input('type', 'photo');
        $videoUrl = trim((string) input('video_url', ''));
        $isActive = (int) input('is_active', 1);
        $sortOrder = input('sort_order', '');

        // Validation
        if (empty($titleInput)) {
            flash('error', 'Judul galeri wajib diisi.');
            redirect('/admin/gallery/create');
        }

        if ($type === 'video' && empty($videoUrl)) {
            flash('error', 'Link video wajib diisi untuk tipe video.');
            redirect('/admin/gallery/create');
        }

        // Tipe foto mewajibkan upload file
        if ($type === 'photo' && (!isset($_FILES['file_path']) || $_FILES['file_path']['error'] !== UPLOAD_ERR_OK)) {
            flash('error', 'File foto wajib diunggah.');
            redirect('/admin/gallery/create');
        }

        $filePath = '';
        $thumbnailPath = '';

        // Handle file upload untuk foto atau thumbnail video
        if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file_path']['tmp_name'];
            $fileName = $_FILES['file_path']['name'];
            $fileSize = $_FILES['file_path']['size'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                flash('error', 'Ekstensi file tidak valid. Hanya JPG, JPEG, PNG, dan WEBP.');
                redirect('/admin/gallery/create');
            }

            if ($fileSize > UPLOAD_MAX_SIZE) {
                flash('error', 'Ukuran file tidak boleh melebihi 5MB.');
                redirect('/admin/gallery/create');
            }

            $uploadFileDir = BASE_PATH . '/uploads/gallery/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $destPath = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $filePath = 'uploads/gallery/' . $newFileName;
                $thumbnailPath = 'uploads/gallery/' . $newFileName; // Gunakan gambar yang sama sebagai thumbnail
            } else {
                flash('error', 'Gagal mengunggah file galeri.');
                redirect('/admin/gallery/create');
            }
        }

        $data = [
            'title' => $titleInput,
            'description' => $description,
            'album' => $album !== '' ? $album : 'Umum',
            'type' => $type,
            'video_url' => $type === 'video' ? $videoUrl : null,
            'is_active' => $isActive,
        ];

        if ($filePath !== '') {
            $data['file_path'] = $filePath;
            $data['thumbnail_path'] = $thumbnailPath;
        }

        if ($sortOrder !== '') {
            $data['sort_order'] = (int) $sortOrder;
        }

        try {
            $this->galleryModel->create($data);
            flash('success', 'Item galeri berhasil ditambahkan.');
            redirect('/admin/gallery');
        } catch (\Exception $e) {
            // Hapus file yang baru diunggah jika db insert gagal
            if ($filePath !== '' && file_exists(BASE_PATH . '/' . $filePath)) {
                @unlink(BASE_PATH . '/' . $filePath);
            }
            flash('error', 'Gagal menyimpan item galeri: ' . $e->getMessage());
            redirect('/admin/gallery/create');
        }
    }

    /**
     * Tampilkan form edit item galeri
     */
    public function edit(string $idStr): void
    {
        $id = (int) $idStr;
        $gallery = $this->galleryModel->getById($id);

        if (!$gallery) {
            flash('error', 'Item galeri tidak ditemukan.');
            redirect('/admin/gallery');
        }

        $title = 'Edit Galeri: ' . $gallery['title'];
        
        // Dapatkan daftar album yang sudah ada
        $albums = $this->galleryModel->getAlbums();

        ob_start();
        require VIEWS_PATH . '/admin/gallery/edit.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Proses simpan perubahan galeri
     */
    public function update(string $idStr): void
    {
        $id = (int) $idStr;
        $gallery = $this->galleryModel->getById($id);

        if (!$gallery) {
            flash('error', 'Item galeri tidak ditemukan.');
            redirect('/admin/gallery');
        }

        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid. Silakan coba lagi.');
            redirect('/admin/gallery/edit/' . $id);
        }

        $titleInput = trim((string) input('title', ''));
        $description = trim((string) input('description', ''));
        $album = trim((string) input('album', ''));
        $type = input('type', 'photo');
        $videoUrl = trim((string) input('video_url', ''));
        $isActive = (int) input('is_active', 1);
        $sortOrder = (int) input('sort_order', 0);

        // Validation
        if (empty($titleInput)) {
            flash('error', 'Judul galeri wajib diisi.');
            redirect('/admin/gallery/edit/' . $id);
        }

        if ($type === 'video' && empty($videoUrl)) {
            flash('error', 'Link video wajib diisi untuk tipe video.');
            redirect('/admin/gallery/edit/' . $id);
        }

        $filePath = '';
        $thumbnailPath = '';

        // Handle file upload baru jika ada
        if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file_path']['tmp_name'];
            $fileName = $_FILES['file_path']['name'];
            $fileSize = $_FILES['file_path']['size'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                flash('error', 'Ekstensi file tidak valid. Hanya JPG, JPEG, PNG, dan WEBP.');
                redirect('/admin/gallery/edit/' . $id);
            }

            if ($fileSize > UPLOAD_MAX_SIZE) {
                flash('error', 'Ukuran file tidak boleh melebihi 5MB.');
                redirect('/admin/gallery/edit/' . $id);
            }

            $uploadFileDir = BASE_PATH . '/uploads/gallery/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $destPath = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $filePath = 'uploads/gallery/' . $newFileName;
                $thumbnailPath = 'uploads/gallery/' . $newFileName;

                // Hapus file lama jika ada dan tipe lamanya adalah photo
                if (!empty($gallery['file_path']) && file_exists(BASE_PATH . '/' . $gallery['file_path'])) {
                    @unlink(BASE_PATH . '/' . $gallery['file_path']);
                }
            } else {
                flash('error', 'Gagal mengunggah file galeri.');
                redirect('/admin/gallery/edit/' . $id);
            }
        }

        $data = [
            'title' => $titleInput,
            'description' => $description,
            'album' => $album !== '' ? $album : 'Umum',
            'type' => $type,
            'video_url' => $type === 'video' ? $videoUrl : null,
            'is_active' => $isActive,
            'sort_order' => $sortOrder,
        ];

        if ($filePath !== '') {
            $data['file_path'] = $filePath;
            $data['thumbnail_path'] = $thumbnailPath;
        }

        try {
            $this->galleryModel->update($id, $data);
            flash('success', 'Item galeri berhasil diperbarui.');
            redirect('/admin/gallery');
        } catch (\Exception $e) {
            // Hapus file baru yang gagal di-update ke DB
            if ($filePath !== '' && file_exists(BASE_PATH . '/' . $filePath)) {
                @unlink(BASE_PATH . '/' . $filePath);
            }
            flash('error', 'Gagal memperbarui galeri: ' . $e->getMessage());
            redirect('/admin/gallery/edit/' . $id);
        }
    }

    /**
     * Proses hapus item galeri
     */
    public function delete(string $idStr): void
    {
        $id = (int) $idStr;
        $gallery = $this->galleryModel->getById($id);

        if (!$gallery) {
            flash('error', 'Item galeri tidak ditemukan.');
            redirect('/admin/gallery');
        }

        try {
            // Gallery model delete() automatically deletes physical files
            $this->galleryModel->delete($id);
            flash('success', 'Item galeri berhasil dihapus.');
        } catch (\Exception $e) {
            flash('error', 'Gagal menghapus galeri: ' . $e->getMessage());
        }

        redirect('/admin/gallery');
    }
}
