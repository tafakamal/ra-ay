<?php
/**
 * Model Teacher (Guru & Tenaga Pendidik)
 * RA Attakal Yaqiin - Website Sekolah
 *
 * Mengelola data guru dan tenaga pendidik.
 */

declare(strict_types=1);

class Teacher
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Ambil semua guru
     *
     * @param bool $activeOnly Hanya tampilkan guru aktif (default: true)
     * @return array Daftar guru
     */
    public function getAll(bool $activeOnly = true): array
    {
        if ($activeOnly) {
            $sql = "SELECT * FROM teachers
                    WHERE is_active = 1
                    ORDER BY sort_order ASC, name ASC";
            return $this->db->fetchAll($sql);
        }

        $sql = "SELECT * FROM teachers ORDER BY sort_order ASC, name ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Ambil guru berdasarkan ID
     *
     * @param int $id ID guru
     * @return array|null Data guru atau null
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM teachers WHERE id = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Ambil guru berdasarkan jabatan
     *
     * @param string $position Jabatan guru
     * @return array Daftar guru dengan jabatan tersebut
     */
    public function getByPosition(string $position): array
    {
        $sql = "SELECT * FROM teachers
                WHERE position LIKE ? AND is_active = 1
                ORDER BY sort_order ASC";

        return $this->db->fetchAll($sql, ["%{$position}%"]);
    }

    /**
     * Hitung jumlah guru aktif
     *
     * @return int Jumlah guru aktif
     */
    public function getActiveCount(): int
    {
        return $this->db->count('teachers', 'is_active = 1');
    }

    // ========================================================
    // Method untuk Admin
    // ========================================================

    /**
     * Tambah data guru baru
     *
     * @param array $data Data guru
     * @return int ID guru baru
     */
    public function create(array $data): int
    {
        // Tentukan sort_order otomatis jika tidak diset
        if (!isset($data['sort_order'])) {
            $maxOrder = $this->db->fetchColumn("SELECT MAX(sort_order) FROM teachers");
            $data['sort_order'] = ($maxOrder !== false ? (int) $maxOrder : 0) + 1;
        }

        return $this->db->insert('teachers', $data);
    }

    /**
     * Update data guru
     *
     * @param int $id ID guru
     * @param array $data Data yang diupdate
     * @return int Jumlah baris terpengaruh
     */
    public function update(int $id, array $data): int
    {
        return $this->db->update('teachers', $data, 'id = ?', [$id]);
    }

    /**
     * Hapus data guru
     *
     * @param int $id ID guru
     * @return int Jumlah baris yang dihapus
     */
    public function delete(int $id): int
    {
        // Hapus foto jika ada
        $teacher = $this->getById($id);
        if ($teacher && !empty($teacher['photo'])) {
            $photoPath = BASE_PATH . '/' . $teacher['photo'];
            if (file_exists($photoPath)) {
                @unlink($photoPath);
            }
        }

        return $this->db->delete('teachers', 'id = ?', [$id]);
    }

    /**
     * Ubah urutan guru
     *
     * @param array $orders Array [id => sort_order]
     */
    public function reorder(array $orders): void
    {
        $this->db->beginTransaction();
        try {
            foreach ($orders as $id => $order) {
                $this->db->update(
                    'teachers',
                    ['sort_order' => (int) $order],
                    'id = ?',
                    [(int) $id]
                );
            }
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Toggle status aktif/nonaktif guru
     *
     * @param int $id ID guru
     * @return bool Status baru setelah toggle
     */
    public function toggleActive(int $id): bool
    {
        $teacher = $this->getById($id);
        if (!$teacher) {
            throw new RuntimeException("Guru dengan ID {$id} tidak ditemukan.");
        }

        $newStatus = $teacher['is_active'] ? 0 : 1;
        $this->db->update('teachers', ['is_active' => $newStatus], 'id = ?', [$id]);

        return (bool) $newStatus;
    }
}
