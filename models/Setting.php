<?php
/**
 * Model Setting (Pengaturan Situs)
 * RA Attakal Yaqiin - Website Sekolah
 *
 * Mengelola pengaturan situs dengan caching di memori
 * untuk menghindari query berulang ke database.
 */

declare(strict_types=1);

class Setting
{
    private Database $db;

    /**
     * Cache pengaturan di memori
     * Setelah pertama kali dimuat, tidak perlu query lagi
     */
    private static ?array $cache = null;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Muat semua pengaturan ke cache (lazy loading)
     * Hanya dilakukan sekali per request
     */
    private function loadCache(): void
    {
        if (self::$cache === null) {
            $sql = "SELECT setting_key, setting_value FROM settings";
            $rows = $this->db->fetchAll($sql);

            self::$cache = [];
            foreach ($rows as $row) {
                self::$cache[$row['setting_key']] = $row['setting_value'];
            }
        }
    }

    /**
     * Ambil nilai pengaturan berdasarkan key
     *
     * @param string $key Kunci pengaturan
     * @param string|null $default Nilai default jika key tidak ditemukan
     * @return string|null Nilai pengaturan
     */
    public function get(string $key, ?string $default = null): ?string
    {
        $this->loadCache();
        return self::$cache[$key] ?? $default;
    }

    /**
     * Ambil semua pengaturan
     *
     * @return array Semua pengaturan [key => value]
     */
    public function getAll(): array
    {
        $this->loadCache();
        return self::$cache;
    }

    /**
     * Ambil beberapa pengaturan sekaligus
     *
     * @param array $keys Array kunci pengaturan yang diinginkan
     * @return array Pengaturan yang diminta [key => value]
     */
    public function getMultiple(array $keys): array
    {
        $this->loadCache();

        $result = [];
        foreach ($keys as $key) {
            $result[$key] = self::$cache[$key] ?? null;
        }

        return $result;
    }

    /**
     * Ambil pengaturan berdasarkan group
     *
     * @param string $group Nama group pengaturan
     * @return array Pengaturan dalam group [key => value]
     */
    public function getByGroup(string $group): array
    {
        $sql = "SELECT setting_key, setting_value
                FROM settings
                WHERE setting_group = ?
                ORDER BY setting_key ASC";

        $rows = $this->db->fetchAll($sql, [$group]);

        $result = [];
        foreach ($rows as $row) {
            $result[$row['setting_key']] = $row['setting_value'];
        }

        return $result;
    }

    /**
     * Simpan atau update pengaturan
     *
     * @param string $key Kunci pengaturan
     * @param string|null $value Nilai pengaturan
     * @param string $group Group pengaturan (default: 'general')
     */
    public function set(string $key, ?string $value, string $group = 'general'): void
    {
        // Cek apakah key sudah ada
        $exists = $this->db->count('settings', 'setting_key = ?', [$key]);

        if ($exists > 0) {
            $this->db->update(
                'settings',
                ['setting_value' => $value],
                'setting_key = ?',
                [$key]
            );
        } else {
            $this->db->insert('settings', [
                'setting_key'   => $key,
                'setting_value' => $value,
                'setting_group' => $group,
            ]);
        }

        // Update cache
        if (self::$cache !== null) {
            self::$cache[$key] = $value;
        }
    }

    /**
     * Simpan banyak pengaturan sekaligus
     *
     * @param array $settings Pengaturan [key => value]
     * @param string $group Group pengaturan
     */
    public function setMultiple(array $settings, string $group = 'general'): void
    {
        $this->db->beginTransaction();

        try {
            foreach ($settings as $key => $value) {
                $this->set($key, $value, $group);
            }
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Hapus pengaturan
     *
     * @param string $key Kunci pengaturan yang akan dihapus
     * @return int Jumlah baris yang dihapus
     */
    public function delete(string $key): int
    {
        $result = $this->db->delete('settings', 'setting_key = ?', [$key]);

        // Hapus dari cache
        if (self::$cache !== null) {
            unset(self::$cache[$key]);
        }

        return $result;
    }

    /**
     * Reset cache (berguna setelah update massal)
     */
    public function clearCache(): void
    {
        self::$cache = null;
    }

    /**
     * Ambil semua pengaturan untuk admin panel (termasuk group info)
     *
     * @return array Semua pengaturan dengan info lengkap
     */
    public function getAllAdmin(): array
    {
        $sql = "SELECT * FROM settings ORDER BY setting_group ASC, setting_key ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Ambil daftar group yang tersedia
     *
     * @return array Daftar nama group
     */
    public function getGroups(): array
    {
        $sql = "SELECT DISTINCT setting_group FROM settings ORDER BY setting_group ASC";
        $rows = $this->db->fetchAll($sql);
        return array_column($rows, 'setting_group');
    }
}
