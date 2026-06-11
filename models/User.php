<?php
/**
 * Model User (Pengguna / Admin)
 * RA Attakal Yaqiin - Website Sekolah
 *
 * Mengelola autentikasi dan data pengguna admin.
 */

declare(strict_types=1);

class User
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Autentikasi pengguna dengan email dan password
     *
     * @param string $email Email pengguna
     * @param string $password Password (plain text)
     * @return array|null Data pengguna jika berhasil, null jika gagal
     */
    public function authenticate(string $email, string $password): ?array
    {
        $sql = "SELECT * FROM users WHERE email = ? AND is_active = 1 LIMIT 1";
        $user = $this->db->fetch($sql, [$email]);

        if (!$user) {
            return null;
        }

        // Verifikasi password
        if (!password_verify($password, $user['password'])) {
            return null;
        }

        // Update waktu login terakhir
        $this->db->update(
            'users',
            ['last_login' => date('Y-m-d H:i:s')],
            'id = ?',
            [$user['id']]
        );

        // Cek apakah hash password perlu di-rehash (upgrade algoritma)
        if (password_needs_rehash($user['password'], PASSWORD_BCRYPT, ['cost' => 12])) {
            $this->updatePassword($user['id'], $password);
        }

        // Hapus password dari data yang dikembalikan (keamanan)
        unset($user['password'], $user['remember_token']);

        return $user;
    }

    /**
     * Ambil data pengguna berdasarkan ID
     *
     * @param int $id ID pengguna
     * @return array|null Data pengguna atau null
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT id, name, email, role, avatar, is_active, last_login, created_at
                FROM users
                WHERE id = ?
                LIMIT 1";

        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Ambil data pengguna berdasarkan email
     *
     * @param string $email Email pengguna
     * @return array|null Data pengguna atau null
     */
    public function getByEmail(string $email): ?array
    {
        $sql = "SELECT id, name, email, role, avatar, is_active, last_login, created_at
                FROM users
                WHERE email = ?
                LIMIT 1";

        return $this->db->fetch($sql, [$email]);
    }

    /**
     * Update password pengguna
     *
     * @param int $id ID pengguna
     * @param string $newPassword Password baru (plain text, akan di-hash)
     * @return int Jumlah baris terpengaruh
     */
    public function updatePassword(int $id, string $newPassword): int
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);

        return $this->db->update(
            'users',
            ['password' => $hashedPassword],
            'id = ?',
            [$id]
        );
    }

    /**
     * Verifikasi password lama pengguna
     *
     * @param int $id ID pengguna
     * @param string $password Password yang akan diverifikasi
     * @return bool True jika cocok
     */
    public function verifyPassword(int $id, string $password): bool
    {
        $sql = "SELECT password FROM users WHERE id = ? LIMIT 1";
        $user = $this->db->fetch($sql, [$id]);

        if (!$user) {
            return false;
        }

        return password_verify($password, $user['password']);
    }

    /**
     * Update profil pengguna
     *
     * @param int $id ID pengguna
     * @param array $data Data yang diupdate (name, email, avatar)
     * @return int Jumlah baris terpengaruh
     */
    public function updateProfile(int $id, array $data): int
    {
        // Hanya izinkan kolom tertentu yang di-update
        $allowed = ['name', 'email', 'avatar'];
        $filtered = array_intersect_key($data, array_flip($allowed));

        if (empty($filtered)) {
            return 0;
        }

        return $this->db->update('users', $filtered, 'id = ?', [$id]);
    }

    /**
     * Set remember token untuk fitur "Ingat Saya"
     *
     * @param int $id ID pengguna
     * @param string|null $token Token atau null untuk menghapus
     * @return int Jumlah baris terpengaruh
     */
    public function setRememberToken(int $id, ?string $token): int
    {
        return $this->db->update(
            'users',
            ['remember_token' => $token],
            'id = ?',
            [$id]
        );
    }

    /**
     * Ambil pengguna berdasarkan remember token
     *
     * @param string $token Remember token
     * @return array|null Data pengguna atau null
     */
    public function getByRememberToken(string $token): ?array
    {
        $sql = "SELECT id, name, email, role, avatar, is_active
                FROM users
                WHERE remember_token = ? AND is_active = 1
                LIMIT 1";

        return $this->db->fetch($sql, [$token]);
    }

    // ========================================================
    // Method untuk Admin
    // ========================================================

    /**
     * Ambil semua pengguna
     *
     * @return array Daftar semua pengguna
     */
    public function getAll(): array
    {
        $sql = "SELECT id, name, email, role, avatar, is_active, last_login, created_at
                FROM users
                ORDER BY created_at ASC";

        return $this->db->fetchAll($sql);
    }

    /**
     * Buat pengguna baru
     *
     * @param array $data Data pengguna
     * @return int ID pengguna baru
     */
    public function create(array $data): int
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }

        return $this->db->insert('users', $data);
    }

    /**
     * Hapus pengguna
     *
     * @param int $id ID pengguna
     * @return int Jumlah baris yang dihapus
     */
    public function delete(int $id): int
    {
        // Cegah menghapus diri sendiri
        if (isset($_SESSION['user_id']) && (int) $_SESSION['user_id'] === $id) {
            throw new RuntimeException("Anda tidak dapat menghapus akun Anda sendiri.");
        }

        return $this->db->delete('users', 'id = ?', [$id]);
    }

    /**
     * Cek apakah email sudah digunakan
     *
     * @param string $email Email yang akan dicek
     * @param int|null $excludeId ID yang dikecualikan (untuk update)
     * @return bool True jika email sudah digunakan
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $params = [$email];
        $where = "email = ?";

        if ($excludeId) {
            $where .= " AND id != ?";
            $params[] = $excludeId;
        }

        return $this->db->count('users', $where, $params) > 0;
    }
}
