<?php
/**
 * Controller AdminProfile
 * RA Attakal Yaqiin
 */

declare(strict_types=1);

class AdminProfileController
{
    private User $userModel;

    public function __construct()
    {
        require_auth();
        $this->userModel = new User();
    }

    /**
     * Tampilkan form edit profil & ubah password
     */
    public function index(): void
    {
        $title = 'Profil Akun';

        // Ambil data user terbaru dari DB
        $user = $this->userModel->getById($_SESSION['user_id']);

        if (!$user) {
            flash('error', 'Data pengguna tidak ditemukan.');
            redirect('/admin/logout');
        }

        ob_start();
        require VIEWS_PATH . '/admin/profile/index.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Proses update profil (nama & email)
     */
    public function update(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid. Silakan coba lagi.');
            redirect('/admin/profile');
        }

        $userId = $_SESSION['user_id'];
        $name = trim((string) input('name', ''));
        $email = trim((string) input('email', ''));

        // Validation
        if (empty($name) || empty($email)) {
            flash('error', 'Nama dan email wajib diisi.');
            redirect('/admin/profile');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Format email tidak valid.');
            redirect('/admin/profile');
        }

        // Cek email duplikat
        if ($this->userModel->emailExists($email, $userId)) {
            flash('error', 'Email sudah digunakan oleh pengguna lain.');
            redirect('/admin/profile');
        }

        $avatarPath = '';

        // Handle avatar upload (jika ada)
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['avatar']['tmp_name'];
            $fileName = $_FILES['avatar']['name'];
            $fileSize = $_FILES['avatar']['size'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                flash('error', 'Ekstensi file tidak valid. Hanya JPG, JPEG, PNG, dan WEBP.');
                redirect('/admin/profile');
            }

            if ($fileSize > UPLOAD_MAX_SIZE) {
                flash('error', 'Ukuran avatar tidak boleh melebihi 5MB.');
                redirect('/admin/profile');
            }

            $uploadFileDir = BASE_PATH . '/uploads/avatars/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $newFileName = 'avatar_' . $userId . '_' . time() . '.' . $fileExtension;
            $destPath = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $avatarPath = 'uploads/avatars/' . $newFileName;

                // Hapus avatar lama jika ada (bukan default placeholder)
                $currentUser = $this->userModel->getById($userId);
                if ($currentUser && !empty($currentUser['avatar'])) {
                    $oldFilePath = BASE_PATH . '/' . $currentUser['avatar'];
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
                    }
                }
            } else {
                flash('error', 'Gagal mengunggah avatar.');
                redirect('/admin/profile');
            }
        }

        $data = [
            'name' => $name,
            'email' => $email,
        ];

        if ($avatarPath !== '') {
            $data['avatar'] = $avatarPath;
        }

        try {
            $this->userModel->updateProfile($userId, $data);
            
            // Perbarui data user di session
            $_SESSION['user'] = $this->userModel->getById($userId);

            flash('success', 'Profil Anda berhasil diperbarui.');
        } catch (\Exception $e) {
            // Hapus file baru jika gagal menyimpan di database
            if ($avatarPath !== '' && file_exists(BASE_PATH . '/' . $avatarPath)) {
                @unlink(BASE_PATH . '/' . $avatarPath);
            }
            flash('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }

        redirect('/admin/profile');
    }

    /**
     * Proses ubah password
     */
    public function changePassword(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid. Silakan coba lagi.');
            redirect('/admin/profile');
        }

        $userId = $_SESSION['user_id'];
        $oldPassword = input('old_password', '');
        $newPassword = input('new_password', '');
        $confirmPassword = input('confirm_password', '');

        // Validation
        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            flash('error', 'Semua kolom password wajib diisi.');
            redirect('/admin/profile');
        }

        if ($newPassword !== $confirmPassword) {
            flash('error', 'Konfirmasi password baru tidak cocok.');
            redirect('/admin/profile');
        }

        if (strlen((string) $newPassword) < 6) {
            flash('error', 'Password baru minimal harus 6 karakter.');
            redirect('/admin/profile');
        }

        // Verifikasi password lama
        if (!$this->userModel->verifyPassword($userId, (string) $oldPassword)) {
            flash('error', 'Password lama yang Anda masukkan salah.');
            redirect('/admin/profile');
        }

        try {
            $this->userModel->updatePassword($userId, (string) $newPassword);
            flash('success', 'Password Anda berhasil diubah.');
        } catch (\Exception $e) {
            flash('error', 'Gagal mengubah password: ' . $e->getMessage());
        }

        redirect('/admin/profile');
    }
}
