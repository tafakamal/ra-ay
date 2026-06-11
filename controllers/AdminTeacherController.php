<?php
/**
 * Controller AdminTeacher
 * RA Attakal Yaqiin
 */

declare(strict_types=1);

class AdminTeacherController
{
    private Teacher $teacherModel;

    public function __construct()
    {
        require_auth();
        $this->teacherModel = new Teacher();
    }

    /**
     * Tampilkan daftar guru & staf
     */
    public function index(): void
    {
        $title = 'Profil Guru & Staf';

        // Ambil semua guru (termasuk yang nonaktif)
        $teachers = $this->teacherModel->getAll(false);

        ob_start();
        require VIEWS_PATH . '/admin/teachers/index.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Tampilkan form tambah guru baru
     */
    public function create(): void
    {
        $title = 'Tambah Guru Baru';

        ob_start();
        require VIEWS_PATH . '/admin/teachers/create.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Proses simpan guru baru
     */
    public function store(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid. Silakan coba lagi.');
            redirect('/admin/teachers/create');
        }

        $name = trim((string) input('name', ''));
        $nip = trim((string) input('nip', ''));
        $position = trim((string) input('position', ''));
        $isActive = (int) input('is_active', 1);
        $sortOrder = input('sort_order', '');

        // Server-side validation
        if (empty($name)) {
            flash('error', 'Nama lengkap guru wajib diisi.');
            redirect('/admin/teachers/create');
        }
        if (empty($position)) {
            flash('error', 'Jabatan guru wajib diisi.');
            redirect('/admin/teachers/create');
        }

        $photoPath = '';

        // Handle file upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['photo']['tmp_name'];
            $fileName = $_FILES['photo']['name'];
            $fileSize = $_FILES['photo']['size'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                flash('error', 'Ekstensi file tidak valid. Hanya JPG, JPEG, PNG, dan WEBP.');
                redirect('/admin/teachers/create');
            }

            if ($fileSize > UPLOAD_MAX_SIZE) {
                flash('error', 'Ukuran foto tidak boleh melebihi 5MB.');
                redirect('/admin/teachers/create');
            }

            $uploadFileDir = BASE_PATH . '/uploads/teachers/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $destPath = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $photoPath = 'uploads/teachers/' . $newFileName;
            } else {
                flash('error', 'Gagal mengunggah foto guru.');
                redirect('/admin/teachers/create');
            }
        }

        $data = [
            'name' => $name,
            'nip' => $nip !== '' ? $nip : null,
            'position' => $position,
            'is_active' => $isActive,
        ];

        if ($photoPath !== '') {
            $data['photo'] = $photoPath;
        }

        if ($sortOrder !== '') {
            $data['sort_order'] = (int) $sortOrder;
        }

        try {
            $this->teacherModel->create($data);
            flash('success', 'Profil guru berhasil ditambahkan.');
            redirect('/admin/teachers');
        } catch (\Exception $e) {
            // Hapus file yang sudah diunggah jika database gagal
            if ($photoPath !== '' && file_exists(BASE_PATH . '/' . $photoPath)) {
                @unlink(BASE_PATH . '/' . $photoPath);
            }
            flash('error', 'Gagal menyimpan data guru: ' . $e->getMessage());
            redirect('/admin/teachers/create');
        }
    }

    /**
     * Tampilkan form edit guru
     */
    public function edit(string $idStr): void
    {
        $id = (int) $idStr;
        $teacher = $this->teacherModel->getById($id);

        if (!$teacher) {
            flash('error', 'Guru tidak ditemukan.');
            redirect('/admin/teachers');
        }

        $title = 'Edit Guru: ' . $teacher['name'];

        ob_start();
        require VIEWS_PATH . '/admin/teachers/edit.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Proses simpan perubahan data guru
     */
    public function update(string $idStr): void
    {
        $id = (int) $idStr;
        $teacher = $this->teacherModel->getById($id);

        if (!$teacher) {
            flash('error', 'Guru tidak ditemukan.');
            redirect('/admin/teachers');
        }

        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid. Silakan coba lagi.');
            redirect('/admin/teachers/edit/' . $id);
        }

        $name = trim((string) input('name', ''));
        $nip = trim((string) input('nip', ''));
        $position = trim((string) input('position', ''));
        $isActive = (int) input('is_active', 1);
        $sortOrder = (int) input('sort_order', 0);

        // Server-side validation
        if (empty($name)) {
            flash('error', 'Nama lengkap guru wajib diisi.');
            redirect('/admin/teachers/edit/' . $id);
        }
        if (empty($position)) {
            flash('error', 'Jabatan guru wajib diisi.');
            redirect('/admin/teachers/edit/' . $id);
        }

        $photoPath = '';

        // Handle file upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['photo']['tmp_name'];
            $fileName = $_FILES['photo']['name'];
            $fileSize = $_FILES['photo']['size'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                flash('error', 'Ekstensi file tidak valid. Hanya JPG, JPEG, PNG, dan WEBP.');
                redirect('/admin/teachers/edit/' . $id);
            }

            if ($fileSize > UPLOAD_MAX_SIZE) {
                flash('error', 'Ukuran foto tidak boleh melebihi 5MB.');
                redirect('/admin/teachers/edit/' . $id);
            }

            $uploadFileDir = BASE_PATH . '/uploads/teachers/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $destPath = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $photoPath = 'uploads/teachers/' . $newFileName;

                // Hapus foto lama jika ada
                if (!empty($teacher['photo']) && file_exists(BASE_PATH . '/' . $teacher['photo'])) {
                    @unlink(BASE_PATH . '/' . $teacher['photo']);
                }
            } else {
                flash('error', 'Gagal mengunggah foto baru.');
                redirect('/admin/teachers/edit/' . $id);
            }
        }

        $data = [
            'name' => $name,
            'nip' => $nip !== '' ? $nip : null,
            'position' => $position,
            'is_active' => $isActive,
            'sort_order' => $sortOrder,
        ];

        if ($photoPath !== '') {
            $data['photo'] = $photoPath;
        }

        try {
            $this->teacherModel->update($id, $data);
            flash('success', 'Profil guru berhasil diperbarui.');
            redirect('/admin/teachers');
        } catch (\Exception $e) {
            // Hapus file yang baru diunggah jika database gagal
            if ($photoPath !== '' && file_exists(BASE_PATH . '/' . $photoPath)) {
                @unlink(BASE_PATH . '/' . $photoPath);
            }
            flash('error', 'Gagal memperbarui data guru: ' . $e->getMessage());
            redirect('/admin/teachers/edit/' . $id);
        }
    }

    /**
     * Proses hapus guru
     */
    public function delete(string $idStr): void
    {
        $id = (int) $idStr;
        $teacher = $this->teacherModel->getById($id);

        if (!$teacher) {
            flash('error', 'Guru tidak ditemukan.');
            redirect('/admin/teachers');
        }

        try {
            // Teacher model delete() automatically deletes physical photo
            $this->teacherModel->delete($id);
            flash('success', 'Profil guru berhasil dihapus.');
        } catch (\Exception $e) {
            flash('error', 'Gagal menghapus profil guru: ' . $e->getMessage());
        }

        redirect('/admin/teachers');
    }

    /**
     * Urutkan posisi guru via AJAX POST
     */
    public function reorder(): void
    {
        header('Content-Type: application/json');

        // Terima data dari body request atau POST
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $ids = $data['ids'] ?? $_POST['ids'] ?? [];

        if (empty($ids)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Data ID guru tidak dikirim.']);
            exit;
        }

        try {
            $orders = [];
            foreach ($ids as $index => $id) {
                $orders[(int)$id] = $index + 1;
            }

            $this->teacherModel->reorder($orders);
            echo json_encode(['status' => 'success', 'message' => 'Urutan guru berhasil disimpan.']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah urutan: ' . $e->getMessage()]);
        }
        exit;
    }
}
