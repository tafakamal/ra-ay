<?php
/**
 * Model Category (Kategori Berita)
 * RA Attakal Yaqiin - Website Sekolah
 *
 * Mengelola data kategori berita.
 */

declare(strict_types=1);

class Category
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Ambil semua kategori aktif
     *
     * @return array Daftar kategori
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM categories
                WHERE is_active = 1
                ORDER BY sort_order ASC, name ASC";

        return $this->db->fetchAll($sql);
    }

    /**
     * Ambil kategori berdasarkan slug
     *
     * @param string $slug Slug kategori
     * @return array|null Data kategori atau null
     */
    public function getBySlug(string $slug): ?array
    {
        $sql = "SELECT * FROM categories WHERE slug = ? AND is_active = 1 LIMIT 1";
        return $this->db->fetch($sql, [$slug]);
    }

    /**
     * Ambil kategori berdasarkan ID
     *
     * @param int $id ID kategori
     * @return array|null Data kategori atau null
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM categories WHERE id = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Ambil semua kategori beserta jumlah berita yang dipublikasikan
     *
     * @return array Daftar kategori dengan kolom 'news_count'
     */
    public function getWithCount(): array
    {
        $sql = "SELECT c.*, COUNT(n.id) AS news_count
                FROM categories c
                LEFT JOIN news n ON c.id = n.category_id
                    AND n.status = 'published'
                    AND n.published_at <= NOW()
                WHERE c.is_active = 1
                GROUP BY c.id
                ORDER BY c.sort_order ASC, c.name ASC";

        return $this->db->fetchAll($sql);
    }

    // ========================================================
    // Method untuk Admin
    // ========================================================

    /**
     * Ambil semua kategori untuk admin (termasuk yang nonaktif)
     *
     * @return array Daftar semua kategori
     */
    public function getAllAdmin(): array
    {
        $sql = "SELECT c.*, COUNT(n.id) AS news_count
                FROM categories c
                LEFT JOIN news n ON c.id = n.category_id
                GROUP BY c.id
                ORDER BY c.sort_order ASC, c.name ASC";

        return $this->db->fetchAll($sql);
    }

    /**
     * Buat kategori baru
     *
     * @param array $data Data kategori
     * @return int ID kategori baru
     */
    public function create(array $data): int
    {
        if (empty($data['slug'])) {
            $data['slug'] = slugify($data['name']);
        }

        return $this->db->insert('categories', $data);
    }

    /**
     * Update kategori
     *
     * @param int $id ID kategori
     * @param array $data Data yang diupdate
     * @return int Jumlah baris terpengaruh
     */
    public function update(int $id, array $data): int
    {
        return $this->db->update('categories', $data, 'id = ?', [$id]);
    }

    /**
     * Hapus kategori (berita terkait akan di-set NULL oleh foreign key)
     *
     * @param int $id ID kategori
     * @return int Jumlah baris yang dihapus
     */
    public function delete(int $id): int
    {
        return $this->db->delete('categories', 'id = ?', [$id]);
    }
}
