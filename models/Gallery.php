<?php
/**
 * Model Gallery (Galeri Foto & Video)
 * RA Attakal Yaqiin - Website Sekolah
 *
 * Mengelola data galeri foto dan video sekolah.
 */

declare(strict_types=1);

class Gallery
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Ambil semua item galeri yang aktif, opsional filter berdasarkan album
     *
     * @param string|null $album Nama album (null untuk semua)
     * @return array Daftar item galeri
     */
    public function getAll(?string $album = null): array
    {
        $params = [];

        if ($album !== null) {
            $sql = "SELECT * FROM gallery
                    WHERE is_active = 1 AND album = ?
                    ORDER BY sort_order ASC, created_at DESC";
            $params[] = $album;
        } else {
            $sql = "SELECT * FROM gallery
                    WHERE is_active = 1
                    ORDER BY sort_order ASC, created_at DESC";
        }

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Ambil daftar album (nama unik) beserta jumlah item dan thumbnail
     *
     * @return array Daftar album
     */
    public function getAlbums(): array
    {
        $sql = "SELECT album,
                       COUNT(*) AS item_count,
                       MIN(thumbnail_path) AS thumbnail,
                       MAX(created_at) AS latest_date
                FROM gallery
                WHERE is_active = 1 AND album IS NOT NULL AND album != ''
                GROUP BY album
                ORDER BY latest_date DESC";

        return $this->db->fetchAll($sql);
    }

    /**
     * Ambil item galeri berdasarkan ID
     *
     * @param int $id ID item galeri
     * @return array|null Data item galeri atau null
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM gallery WHERE id = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Ambil item galeri terbaru untuk ditampilkan di halaman utama
     *
     * @param int $limit Jumlah item
     * @return array Daftar item galeri terbaru
     */
    public function getLatest(int $limit = 8): array
    {
        $sql = "SELECT * FROM gallery
                WHERE is_active = 1
                ORDER BY created_at DESC
                LIMIT ?";

        return $this->db->fetchAll($sql, [$limit]);
    }

    // ========================================================
    // Method untuk Admin
    // ========================================================

    /**
     * Ambil semua item galeri untuk admin (termasuk yang nonaktif)
     *
     * @return array Semua item galeri
     */
    public function getAllAdmin(): array
    {
        $sql = "SELECT * FROM gallery ORDER BY sort_order ASC, created_at DESC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Tambah item galeri baru
     *
     * @param array $data Data item galeri
     * @return int ID item baru
     */
    public function create(array $data): int
    {
        // Tentukan sort_order otomatis jika tidak diset
        if (!isset($data['sort_order'])) {
            $maxOrder = $this->db->fetchColumn("SELECT MAX(sort_order) FROM gallery");
            $data['sort_order'] = ($maxOrder !== false ? (int) $maxOrder : 0) + 1;
        }

        return $this->db->insert('gallery', $data);
    }

    /**
     * Update item galeri
     *
     * @param int $id ID item galeri
     * @param array $data Data yang diupdate
     * @return int Jumlah baris terpengaruh
     */
    public function update(int $id, array $data): int
    {
        return $this->db->update('gallery', $data, 'id = ?', [$id]);
    }

    /**
     * Hapus item galeri
     *
     * @param int $id ID item galeri
     * @return int Jumlah baris yang dihapus
     */
    public function delete(int $id): int
    {
        // Ambil data file sebelum dihapus (untuk menghapus file fisik)
        $item = $this->getById($id);

        $result = $this->db->delete('gallery', 'id = ?', [$id]);

        // Hapus file fisik jika berhasil dihapus dari database
        if ($result > 0 && $item) {
            $filePath = BASE_PATH . '/' . $item['file_path'];
            $thumbPath = BASE_PATH . '/' . ($item['thumbnail_path'] ?? '');

            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            if (!empty($item['thumbnail_path']) && file_exists($thumbPath)) {
                @unlink($thumbPath);
            }
        }

        return $result;
    }

    /**
     * Ubah urutan item galeri
     *
     * @param array $orders Array [id => sort_order]
     */
    public function reorder(array $orders): void
    {
        $this->db->beginTransaction();
        try {
            foreach ($orders as $id => $order) {
                $this->db->update('gallery', ['sort_order' => (int) $order], 'id = ?', [(int) $id]);
            }
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
