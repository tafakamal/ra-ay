<?php
/**
 * Model Contact (Pesan Kontak)
 * RA Attakal Yaqiin - Website Sekolah
 *
 * Mengelola pesan kontak dari pengunjung website.
 */

declare(strict_types=1);

class Contact
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Simpan pesan kontak baru
     *
     * @param array $data Data pesan kontak
     * @return int ID pesan yang baru dibuat
     */
    public function create(array $data): int
    {
        // Sanitasi data input
        $insertData = [
            'name'       => trim($data['name'] ?? ''),
            'email'      => trim($data['email'] ?? ''),
            'phone'      => trim($data['phone'] ?? ''),
            'subject'    => trim($data['subject'] ?? ''),
            'message'    => trim($data['message'] ?? ''),
            'ip_address' => get_client_ip(),
        ];

        // Validasi data wajib
        $errors = [];
        if (empty($insertData['name'])) {
            $errors[] = 'Nama wajib diisi.';
        }
        if (empty($insertData['email']) || !filter_var($insertData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email tidak valid.';
        }
        if (empty($insertData['subject'])) {
            $errors[] = 'Subjek wajib diisi.';
        }
        if (empty($insertData['message'])) {
            $errors[] = 'Pesan wajib diisi.';
        }
        if (mb_strlen($insertData['message']) < 10) {
            $errors[] = 'Pesan minimal 10 karakter.';
        }

        if (!empty($errors)) {
            throw new InvalidArgumentException(implode(' ', $errors));
        }

        // Cek rate limiting: maksimal 5 pesan per jam dari IP yang sama
        $recentCount = $this->db->count(
            'contacts',
            'ip_address = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)',
            [$insertData['ip_address']]
        );

        if ($recentCount >= 5) {
            throw new RuntimeException(
                'Anda telah mengirim terlalu banyak pesan. Silakan coba lagi nanti.'
            );
        }

        return $this->db->insert('contacts', $insertData);
    }

    /**
     * Ambil semua pesan kontak dengan pagination
     *
     * @param int $page Halaman saat ini
     * @param int $perPage Jumlah per halaman
     * @return array Data pesan + info pagination
     */
    public function getAll(int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;

        $total = $this->db->count('contacts');
        $totalPages = (int) ceil($total / $perPage);

        $sql = "SELECT * FROM contacts
                ORDER BY created_at DESC
                LIMIT ? OFFSET ?";

        $data = $this->db->fetchAll($sql, [$perPage, $offset]);

        return [
            'data'  => $data,
            'total' => $total,
            'pages' => $totalPages,
            'page'  => $page,
        ];
    }

    /**
     * Ambil pesan kontak yang belum dibaca
     *
     * @return array Daftar pesan belum dibaca
     */
    public function getUnread(): array
    {
        $sql = "SELECT * FROM contacts
                WHERE is_read = 0
                ORDER BY created_at DESC";

        return $this->db->fetchAll($sql);
    }

    /**
     * Hitung jumlah pesan yang belum dibaca
     *
     * @return int Jumlah pesan belum dibaca
     */
    public function getUnreadCount(): int
    {
        return $this->db->count('contacts', 'is_read = 0');
    }

    /**
     * Ambil pesan berdasarkan ID
     *
     * @param int $id ID pesan
     * @return array|null Data pesan atau null
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM contacts WHERE id = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Tandai pesan sebagai sudah dibaca
     *
     * @param int $id ID pesan
     * @return int Jumlah baris terpengaruh
     */
    public function markAsRead(int $id): int
    {
        return $this->db->update(
            'contacts',
            [
                'is_read' => 1,
                'read_at' => date('Y-m-d H:i:s'),
            ],
            'id = ?',
            [$id]
        );
    }

    /**
     * Tandai semua pesan sebagai sudah dibaca
     *
     * @return int Jumlah baris terpengaruh
     */
    public function markAllAsRead(): int
    {
        $sql = "UPDATE contacts SET is_read = 1, read_at = NOW() WHERE is_read = 0";
        $stmt = $this->db->query($sql);
        return $stmt->rowCount();
    }

    /**
     * Hapus pesan kontak
     *
     * @param int $id ID pesan
     * @return int Jumlah baris yang dihapus
     */
    public function delete(int $id): int
    {
        return $this->db->delete('contacts', 'id = ?', [$id]);
    }

    /**
     * Hapus pesan lama (pembersihan otomatis)
     * Menghapus pesan yang sudah dibaca dan lebih dari $days hari
     *
     * @param int $days Jumlah hari
     * @return int Jumlah baris yang dihapus
     */
    public function deleteOld(int $days = 90): int
    {
        return $this->db->delete(
            'contacts',
            'is_read = 1 AND created_at < DATE_SUB(NOW(), INTERVAL ? DAY)',
            [$days]
        );
    }

    /**
     * Ambil statistik pesan kontak
     *
     * @return array Statistik [total, unread, today, this_week]
     */
    public function getStats(): array
    {
        $total = $this->db->count('contacts');
        $unread = $this->db->count('contacts', 'is_read = 0');
        $today = $this->db->count('contacts', 'DATE(created_at) = CURDATE()');
        $thisWeek = $this->db->count(
            'contacts',
            'created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)'
        );

        return [
            'total'     => $total,
            'unread'    => $unread,
            'today'     => $today,
            'this_week' => $thisWeek,
        ];
    }
}
