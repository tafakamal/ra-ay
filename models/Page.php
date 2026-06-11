<?php
/**
 * Model Page (Halaman Statis)
 * RA Attakal Yaqiin - Website Sekolah
 *
 * Mengelola data halaman statis seperti Tentang Sekolah,
 * Visi Misi, Kurikulum, dll.
 */

declare(strict_types=1);

class Page
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Ambil halaman berdasarkan slug
     *
     * @param string $slug Slug halaman
     * @return array|null Data halaman atau null jika tidak ditemukan
     */
    public function getBySlug(string $slug): ?array
    {
        $sql = "SELECT * FROM pages
                WHERE slug = ? AND status = 'published'
                LIMIT 1";

        return $this->db->fetch($sql, [$slug]);
    }

    /**
     * Ambil halaman berdasarkan ID
     *
     * @param int $id ID halaman
     * @return array|null Data halaman atau null
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM pages WHERE id = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Ambil semua halaman yang dipublikasikan
     *
     * @return array Daftar halaman
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM pages
                WHERE status = 'published'
                ORDER BY sort_order ASC, title ASC";

        return $this->db->fetchAll($sql);
    }

    // ========================================================
    // Method untuk Admin
    // ========================================================

    /**
     * Ambil semua halaman untuk admin (termasuk draft)
     *
     * @return array Daftar semua halaman
     */
    public function getAllAdmin(): array
    {
        $sql = "SELECT * FROM pages ORDER BY sort_order ASC, title ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Update halaman
     *
     * @param int $id ID halaman
     * @param array $data Data yang diupdate
     * @return int Jumlah baris terpengaruh
     */
    public function update(int $id, array $data): int
    {
        // Regenerate slug jika diminta
        if (!empty($data['regenerate_slug']) && !empty($data['title'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $id);
            unset($data['regenerate_slug']);
        }

        // Set meta title dari judul jika kosong
        if (isset($data['title']) && empty($data['meta_title'])) {
            $data['meta_title'] = $data['title'] . ' - ' . SITE_NAME;
        }

        return $this->db->update('pages', $data, 'id = ?', [$id]);
    }

    /**
     * Buat halaman baru
     *
     * @param array $data Data halaman
     * @return int ID halaman baru
     */
    public function create(array $data): int
    {
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title']);
        }

        if (empty($data['meta_title'])) {
            $data['meta_title'] = $data['title'] . ' - ' . SITE_NAME;
        }

        return $this->db->insert('pages', $data);
    }

    /**
     * Hapus halaman
     *
     * @param int $id ID halaman
     * @return int Jumlah baris yang dihapus
     */
    public function delete(int $id): int
    {
        return $this->db->delete('pages', 'id = ?', [$id]);
    }

    /**
     * Generate slug unik untuk halaman
     *
     * @param string $title Judul halaman
     * @param int|null $excludeId ID yang dikecualikan
     * @return string Slug unik
     */
    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = slugify($title);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $params = [$slug];
            $where = "slug = ?";

            if ($excludeId) {
                $where .= " AND id != ?";
                $params[] = $excludeId;
            }

            $exists = $this->db->count('pages', $where, $params);

            if ($exists === 0) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Ambil semua halaman yang dipublikasi untuk sitemap
     *
     * @return array Data halaman untuk sitemap
     */
    public function getForSitemap(): array
    {
        $sql = "SELECT slug, updated_at FROM pages WHERE status = 'published' ORDER BY sort_order ASC";
        return $this->db->fetchAll($sql);
    }
}
