<?php
/**
 * Model News (Berita/Artikel)
 * RA Attakal Yaqiin - Website Sekolah
 *
 * Mengelola data berita dan artikel termasuk operasi CRUD,
 * pencarian, dan pengambilan data untuk frontend.
 */

declare(strict_types=1);

class News
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ========================================================
    // Method untuk Frontend (Public)
    // ========================================================

    /**
     * Ambil berita terbaru yang sudah dipublikasi
     *
     * @param int $limit Jumlah berita yang diambil
     * @return array Daftar berita terbaru
     */
    public function getLatest(int $limit = 6): array
    {
        $sql = "SELECT n.*, c.name AS category_name, c.slug AS category_slug,
                       c.color AS category_color, u.name AS author_name
                FROM news n
                LEFT JOIN categories c ON n.category_id = c.id
                LEFT JOIN users u ON n.user_id = u.id
                WHERE n.status = 'published' AND n.published_at <= NOW()
                ORDER BY n.published_at DESC
                LIMIT ?";

        return $this->db->fetchAll($sql, [$limit]);
    }

    /**
     * Ambil berita unggulan (featured)
     *
     * @param int $limit Jumlah berita unggulan
     * @return array Daftar berita unggulan
     */
    public function getFeatured(int $limit = 3): array
    {
        $sql = "SELECT n.*, c.name AS category_name, c.slug AS category_slug,
                       c.color AS category_color, u.name AS author_name
                FROM news n
                LEFT JOIN categories c ON n.category_id = c.id
                LEFT JOIN users u ON n.user_id = u.id
                WHERE n.status = 'published' AND n.is_featured = 1 AND n.published_at <= NOW()
                ORDER BY n.published_at DESC
                LIMIT ?";

        return $this->db->fetchAll($sql, [$limit]);
    }

    /**
     * Ambil semua berita dengan pagination
     *
     * @param int $page Halaman saat ini
     * @param int $perPage Jumlah per halaman
     * @return array ['data' => [], 'total' => int, 'pages' => int, 'page' => int]
     */
    public function getAll(int $page = 1, int $perPage = 12): array
    {
        $offset = ($page - 1) * $perPage;

        $total = $this->db->count('news', "status = 'published' AND published_at <= NOW()");
        $totalPages = (int) ceil($total / $perPage);

        $sql = "SELECT n.*, c.name AS category_name, c.slug AS category_slug,
                       c.color AS category_color, u.name AS author_name
                FROM news n
                LEFT JOIN categories c ON n.category_id = c.id
                LEFT JOIN users u ON n.user_id = u.id
                WHERE n.status = 'published' AND n.published_at <= NOW()
                ORDER BY n.published_at DESC
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
     * Ambil berita berdasarkan slug
     *
     * @param string $slug Slug berita
     * @return array|null Data berita atau null jika tidak ditemukan
     */
    public function getBySlug(string $slug): ?array
    {
        $sql = "SELECT n.*, c.name AS category_name, c.slug AS category_slug,
                       c.color AS category_color, u.name AS author_name
                FROM news n
                LEFT JOIN categories c ON n.category_id = c.id
                LEFT JOIN users u ON n.user_id = u.id
                WHERE n.slug = ? AND n.status = 'published' AND n.published_at <= NOW()
                LIMIT 1";

        return $this->db->fetch($sql, [$slug]);
    }

    /**
     * Ambil berita berdasarkan kategori dengan pagination
     *
     * @param string $categorySlug Slug kategori
     * @param int $page Halaman saat ini
     * @param int $perPage Jumlah per halaman
     * @return array Data berita + info pagination
     */
    public function getByCategory(string $categorySlug, int $page = 1, int $perPage = 12): array
    {
        $offset = ($page - 1) * $perPage;

        $totalSql = "SELECT COUNT(*) FROM news n
                     INNER JOIN categories c ON n.category_id = c.id
                     WHERE c.slug = ? AND n.status = 'published' AND n.published_at <= NOW()";
        $total = (int) $this->db->fetchColumn($totalSql, [$categorySlug]);
        $totalPages = (int) ceil($total / $perPage);

        $sql = "SELECT n.*, c.name AS category_name, c.slug AS category_slug,
                       c.color AS category_color, u.name AS author_name
                FROM news n
                INNER JOIN categories c ON n.category_id = c.id
                LEFT JOIN users u ON n.user_id = u.id
                WHERE c.slug = ? AND n.status = 'published' AND n.published_at <= NOW()
                ORDER BY n.published_at DESC
                LIMIT ? OFFSET ?";

        $data = $this->db->fetchAll($sql, [$categorySlug, $perPage, $offset]);

        return [
            'data'  => $data,
            'total' => $total,
            'pages' => $totalPages,
            'page'  => $page,
        ];
    }

    /**
     * Cari berita berdasarkan kata kunci
     *
     * @param string $keyword Kata kunci pencarian
     * @param int $page Halaman saat ini
     * @param int $perPage Jumlah per halaman
     * @return array Data berita + info pagination
     */
    public function search(string $keyword, int $page = 1, int $perPage = 12): array
    {
        $offset = ($page - 1) * $perPage;
        $searchTerm = "%{$keyword}%";

        $totalSql = "SELECT COUNT(*) FROM news
                     WHERE status = 'published' AND published_at <= NOW()
                     AND (title LIKE ? OR excerpt LIKE ? OR content LIKE ?)";
        $total = (int) $this->db->fetchColumn($totalSql, [$searchTerm, $searchTerm, $searchTerm]);
        $totalPages = (int) ceil($total / $perPage);

        $sql = "SELECT n.*, c.name AS category_name, c.slug AS category_slug,
                       c.color AS category_color, u.name AS author_name
                FROM news n
                LEFT JOIN categories c ON n.category_id = c.id
                LEFT JOIN users u ON n.user_id = u.id
                WHERE n.status = 'published' AND n.published_at <= NOW()
                AND (n.title LIKE ? OR n.excerpt LIKE ? OR n.content LIKE ?)
                ORDER BY n.published_at DESC
                LIMIT ? OFFSET ?";

        $data = $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm, $perPage, $offset]);

        return [
            'data'    => $data,
            'total'   => $total,
            'pages'   => $totalPages,
            'page'    => $page,
            'keyword' => $keyword,
        ];
    }

    /**
     * Tambah jumlah view pada berita
     *
     * @param int $id ID berita
     */
    public function incrementViews(int $id): void
    {
        $sql = "UPDATE news SET views = views + 1 WHERE id = ?";
        $this->db->query($sql, [$id]);
    }

    /**
     * Ambil berita terkait (berdasarkan kategori yang sama)
     *
     * @param int $newsId ID berita saat ini (akan dikecualikan)
     * @param int $limit Jumlah berita terkait
     * @return array Daftar berita terkait
     */
    public function getRelated(int $newsId, int $limit = 4): array
    {
        // Ambil category_id dari berita saat ini
        $currentNews = $this->db->fetch("SELECT category_id FROM news WHERE id = ?", [$newsId]);

        if (!$currentNews || !$currentNews['category_id']) {
            // Jika tidak ada kategori, ambil berita terbaru saja
            $sql = "SELECT n.*, c.name AS category_name, c.slug AS category_slug,
                           c.color AS category_color
                    FROM news n
                    LEFT JOIN categories c ON n.category_id = c.id
                    WHERE n.id != ? AND n.status = 'published' AND n.published_at <= NOW()
                    ORDER BY n.published_at DESC
                    LIMIT ?";
            return $this->db->fetchAll($sql, [$newsId, $limit]);
        }

        $sql = "SELECT n.*, c.name AS category_name, c.slug AS category_slug,
                       c.color AS category_color
                FROM news n
                LEFT JOIN categories c ON n.category_id = c.id
                WHERE n.id != ? AND n.category_id = ?
                AND n.status = 'published' AND n.published_at <= NOW()
                ORDER BY n.published_at DESC
                LIMIT ?";

        $related = $this->db->fetchAll($sql, [$newsId, $currentNews['category_id'], $limit]);

        // Jika berita terkait kurang dari limit, tambahkan dari kategori lain
        if (count($related) < $limit) {
            $remaining = $limit - count($related);
            $excludeIds = array_merge([$newsId], array_column($related, 'id'));
            $placeholders = implode(',', array_fill(0, count($excludeIds), '?'));

            $sql = "SELECT n.*, c.name AS category_name, c.slug AS category_slug,
                           c.color AS category_color
                    FROM news n
                    LEFT JOIN categories c ON n.category_id = c.id
                    WHERE n.id NOT IN ({$placeholders})
                    AND n.status = 'published' AND n.published_at <= NOW()
                    ORDER BY n.published_at DESC
                    LIMIT ?";

            $moreNews = $this->db->fetchAll($sql, [...$excludeIds, $remaining]);
            $related = array_merge($related, $moreNews);
        }

        return $related;
    }

    /**
     * Ambil berita populer berdasarkan jumlah views
     *
     * @param int $limit Jumlah berita
     * @return array Daftar berita populer
     */
    public function getPopular(int $limit = 5): array
    {
        $sql = "SELECT n.id, n.title, n.slug, n.featured_image, n.views, n.published_at,
                       c.name AS category_name, c.slug AS category_slug
                FROM news n
                LEFT JOIN categories c ON n.category_id = c.id
                WHERE n.status = 'published' AND n.published_at <= NOW()
                ORDER BY n.views DESC
                LIMIT ?";

        return $this->db->fetchAll($sql, [$limit]);
    }

    // ========================================================
    // Method untuk Admin (CRUD)
    // ========================================================

    /**
     * Ambil berita berdasarkan ID (untuk admin, tanpa filter status)
     *
     * @param int $id ID berita
     * @return array|null Data berita
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT n.*, c.name AS category_name, u.name AS author_name
                FROM news n
                LEFT JOIN categories c ON n.category_id = c.id
                LEFT JOIN users u ON n.user_id = u.id
                WHERE n.id = ?
                LIMIT 1";

        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Ambil semua berita untuk admin (termasuk draft)
     *
     * @param int $page Halaman saat ini
     * @param int $perPage Jumlah per halaman
     * @param string|null $status Filter berdasarkan status
     * @return array Data berita + info pagination
     */
    public function getAllAdmin(int $page = 1, int $perPage = 20, ?string $status = null): array
    {
        $offset = ($page - 1) * $perPage;
        $params = [];

        $whereClause = '1=1';
        if ($status) {
            $whereClause .= ' AND n.status = ?';
            $params[] = $status;
        }

        $totalSql = "SELECT COUNT(*) FROM news n WHERE {$whereClause}";
        $total = (int) $this->db->fetchColumn($totalSql, $params);
        $totalPages = (int) ceil($total / $perPage);

        $sql = "SELECT n.*, c.name AS category_name, u.name AS author_name
                FROM news n
                LEFT JOIN categories c ON n.category_id = c.id
                LEFT JOIN users u ON n.user_id = u.id
                WHERE {$whereClause}
                ORDER BY n.created_at DESC
                LIMIT ? OFFSET ?";

        $params[] = $perPage;
        $params[] = $offset;

        $data = $this->db->fetchAll($sql, $params);

        return [
            'data'  => $data,
            'total' => $total,
            'pages' => $totalPages,
            'page'  => $page,
        ];
    }

    /**
     * Buat berita baru
     *
     * @param array $data Data berita
     * @return int ID berita yang baru dibuat
     */
    public function create(array $data): int
    {
        // Generate slug jika belum ada
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title']);
        }

        // Set tanggal publikasi jika status published
        if (($data['status'] ?? 'draft') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        // Set meta title dari judul jika kosong
        if (empty($data['meta_title'])) {
            $data['meta_title'] = $data['title'];
        }

        // Set meta description dari excerpt jika kosong
        if (empty($data['meta_description']) && !empty($data['excerpt'])) {
            $data['meta_description'] = truncate($data['excerpt'], 300, '');
        }

        return $this->db->insert('news', $data);
    }

    /**
     * Update berita
     *
     * @param int $id ID berita
     * @param array $data Data yang diupdate
     * @return int Jumlah baris yang terpengaruh
     */
    public function update(int $id, array $data): int
    {
        // Regenerate slug jika judul berubah dan slug baru diminta
        if (isset($data['regenerate_slug']) && $data['regenerate_slug'] && !empty($data['title'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $id);
            unset($data['regenerate_slug']);
        }

        // Set tanggal publikasi saat pertama kali dipublish
        if (($data['status'] ?? '') === 'published') {
            $existing = $this->getById($id);
            if ($existing && $existing['status'] !== 'published') {
                $data['published_at'] = $data['published_at'] ?? date('Y-m-d H:i:s');
            }
        }

        return $this->db->update('news', $data, 'id = ?', [$id]);
    }

    /**
     * Hapus berita
     *
     * @param int $id ID berita
     * @return int Jumlah baris yang dihapus
     */
    public function delete(int $id): int
    {
        return $this->db->delete('news', 'id = ?', [$id]);
    }

    // ========================================================
    // Method Utility
    // ========================================================

    /**
     * Generate slug unik dari judul
     *
     * @param string $title Judul berita
     * @param int|null $excludeId ID yang dikecualikan (untuk update)
     * @return string Slug yang unik
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

            $exists = $this->db->count('news', $where, $params);

            if ($exists === 0) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Ambil semua berita yang dipublikasi untuk sitemap
     *
     * @return array Data berita untuk sitemap
     */
    public function getForSitemap(): array
    {
        $sql = "SELECT slug, published_at, updated_at
                FROM news
                WHERE status = 'published' AND published_at <= NOW()
                ORDER BY published_at DESC";

        return $this->db->fetchAll($sql);
    }

    /**
     * Hitung total berita berdasarkan status
     *
     * @return array Jumlah per status
     */
    public function getCountByStatus(): array
    {
        $sql = "SELECT status, COUNT(*) as total FROM news GROUP BY status";
        $results = $this->db->fetchAll($sql);

        $counts = ['draft' => 0, 'published' => 0, 'archived' => 0, 'total' => 0];
        foreach ($results as $row) {
            $counts[$row['status']] = (int) $row['total'];
            $counts['total'] += (int) $row['total'];
        }

        return $counts;
    }
}
