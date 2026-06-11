<?php
/**
 * Controller Admin
 * RA Attakal Yaqiin
 */

declare(strict_types=1);

class AdminController
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Tampilkan form login admin
     */
    public function loginForm(): void
    {
        // Jika sudah login, redirect ke dashboard
        if (is_logged_in()) {
            redirect('/admin');
        }

        $title = 'Login Admin';
        
        // Render view login langsung (tanpa layout admin)
        require VIEWS_PATH . '/admin/login.php';
    }

    /**
     * Proses autentikasi login
     */
    public function login(): void
    {
        // Validasi CSRF
        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid. Silakan coba lagi.');
            redirect('/admin/login');
        }

        $email = trim(input('email', ''));
        $password = input('password', '');

        // Validasi Input
        if (empty($email) || empty($password)) {
            flash('error', 'Email dan password wajib diisi.');
            redirect('/admin/login');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Format email tidak valid.');
            redirect('/admin/login');
        }

        $userModel = new User();
        $user = $userModel->authenticate($email, $password);

        if ($user) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user;

            flash('success', 'Selamat datang kembali, ' . $user['name'] . '!');
            redirect('/admin');
        } else {
            flash('error', 'Email atau password salah, atau akun Anda dinonaktifkan.');
            redirect('/admin/login');
        }
    }

    /**
     * Proses logout
     */
    public function logout(): void
    {
        // Hapus session
        unset($_SESSION['user_id'], $_SESSION['user']);
        
        // Hapus session cookie jika ada
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        // Mulai session baru untuk flash message
        session_start();
        flash('success', 'Anda telah berhasil logout.');
        redirect('/admin/login');
    }

    /**
     * Halaman Dashboard Admin
     */
    public function dashboard(): void
    {
        require_auth();

        $title = 'Dashboard';

        // Fetch counts
        $counts = [
            'news' => $this->db->count('news'),
            'categories' => $this->db->count('categories'),
            'teachers' => $this->db->count('teachers'),
            'gallery' => $this->db->count('gallery'),
            'unread_contacts' => $this->db->count('contacts', 'is_read = 0')
        ];

        // Fetch 5 latest news
        $newsModel = new News();
        $latestNewsResult = $newsModel->getAllAdmin(1, 5);
        $latestNews = $latestNewsResult['data'] ?? [];

        // Fetch 5 latest messages
        $contactModel = new Contact();
        $latestContactsResult = $contactModel->getAll(1, 5);
        $latestContacts = $latestContactsResult['data'] ?? [];

        // Render view dashboard dengan output buffering pattern
        ob_start();
        require VIEWS_PATH . '/admin/dashboard.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }
}
