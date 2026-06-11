<?php
/**
 * Controller AdminContact
 * RA Attakal Yaqiin
 */

declare(strict_types=1);

class AdminContactController
{
    private Contact $contactModel;

    public function __construct()
    {
        require_auth();
        $this->contactModel = new Contact();
    }

    /**
     * Tampilkan daftar pesan masuk dengan pagination
     */
    public function index(): void
    {
        $title = 'Pesan Masuk';

        $page = (int) input('page', 1);
        if ($page < 1) {
            $page = 1;
        }
        $perPage = ADMIN_PER_PAGE;

        // Ambil data kontak terpaginasi
        $result = $this->contactModel->getAll($page, $perPage);
        $contacts = $result['data'] ?? [];
        $total = $result['total'] ?? 0;
        $totalPages = $result['pages'] ?? 0;
        $unreadCount = $this->contactModel->getUnreadCount();

        ob_start();
        require VIEWS_PATH . '/admin/contacts/index.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Tampilkan detail pesan dan tandai otomatis sebagai sudah dibaca
     */
    public function view(string $idStr): void
    {
        $id = (int) $idStr;
        $message = $this->contactModel->getById($id);

        if (!$message) {
            flash('error', 'Pesan tidak ditemukan.');
            redirect('/admin/contacts');
        }

        $title = 'Detail Pesan: ' . ($message['subject'] !== '' ? $message['subject'] : 'Tanpa Subjek');

        // Jika belum dibaca, tandai sebagai sudah dibaca
        if (!$message['is_read']) {
            $this->contactModel->markAsRead($id);
            // Refresh data setelah di-update agar status di view sudah ter-update
            $message['is_read'] = 1;
        }

        ob_start();
        require VIEWS_PATH . '/admin/contacts/view.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Hapus pesan
     */
    public function delete(string $idStr): void
    {
        $id = (int) $idStr;
        $message = $this->contactModel->getById($id);

        if (!$message) {
            flash('error', 'Pesan tidak ditemukan.');
            redirect('/admin/contacts');
        }

        try {
            $this->contactModel->delete($id);
            flash('success', 'Pesan berhasil dihapus.');
        } catch (\Exception $e) {
            flash('error', 'Gagal menghapus pesan: ' . $e->getMessage());
        }

        redirect('/admin/contacts');
    }

    /**
     * Tandai satu pesan sebagai dibaca (via POST)
     */
    public function markRead(string $idStr): void
    {
        $id = (int) $idStr;

        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid.');
            redirect('/admin/contacts');
        }

        try {
            $this->contactModel->markAsRead($id);
            flash('success', 'Pesan ditandai sebagai sudah dibaca.');
        } catch (\Exception $e) {
            flash('error', 'Gagal memproses pesan: ' . $e->getMessage());
        }

        redirect('/admin/contacts');
    }

    /**
     * Tandai semua pesan masuk sebagai sudah dibaca (via POST)
     */
    public function markAllRead(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid.');
            redirect('/admin/contacts');
        }

        try {
            $this->contactModel->markAllAsRead();
            flash('success', 'Semua pesan berhasil ditandai sebagai sudah dibaca.');
        } catch (\Exception $e) {
            flash('error', 'Gagal memproses pesan: ' . $e->getMessage());
        }

        redirect('/admin/contacts');
    }
}
