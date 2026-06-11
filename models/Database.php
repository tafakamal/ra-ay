<?php
/**
 * Model Database - Singleton PDO Connection
 * RA Attakal Yaqiin - Website Sekolah
 *
 * Menyediakan koneksi database PDO dengan pola Singleton
 * dan method helper untuk operasi CRUD.
 */

declare(strict_types=1);

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    /**
     * Constructor - buat koneksi PDO
     * Private untuk mencegah instansiasi langsung (Singleton)
     */
    private function __construct()
    {
        $config = require BASE_PATH . '/config/database.php';

        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            $this->pdo = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $e) {
            if (defined('APP_DEBUG') && APP_DEBUG) {
                throw new RuntimeException(
                    "Koneksi database gagal: " . $e->getMessage()
                );
            }
            // Pada mode production, tampilkan pesan umum
            error_log("Database connection failed: " . $e->getMessage());
            throw new RuntimeException(
                "Maaf, terjadi gangguan pada sistem. Silakan coba beberapa saat lagi."
            );
        }
    }

    /**
     * Cegah cloning objek (bagian dari pola Singleton)
     */
    private function __clone() {}

    /**
     * Cegah unserialization (bagian dari pola Singleton)
     */
    public function __wakeup()
    {
        throw new RuntimeException("Tidak dapat melakukan unserialize pada Singleton.");
    }

    /**
     * Mendapatkan instance tunggal Database (Singleton)
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Mendapatkan objek PDO langsung
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Jalankan query dengan prepared statement
     *
     * @param string $sql Query SQL dengan placeholder
     * @param array $params Parameter untuk binding
     * @return PDOStatement
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            if (defined('APP_DEBUG') && APP_DEBUG) {
                throw new RuntimeException(
                    "Query gagal: {$e->getMessage()}\nSQL: {$sql}\nParams: " . json_encode($params)
                );
            }
            error_log("Query failed: {$e->getMessage()} | SQL: {$sql}");
            throw new RuntimeException("Terjadi kesalahan pada database.");
        }
    }

    /**
     * Ambil satu baris data
     *
     * @param string $sql Query SQL
     * @param array $params Parameter binding
     * @return array|null Baris data atau null
     */
    public function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Ambil semua baris data
     *
     * @param string $sql Query SQL
     * @param array $params Parameter binding
     * @return array Array berisi baris-baris data
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Ambil satu nilai (kolom pertama dari baris pertama)
     *
     * @param string $sql Query SQL
     * @param array $params Parameter binding
     * @return mixed Nilai tunggal atau false
     */
    public function fetchColumn(string $sql, array $params = []): mixed
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn();
    }

    /**
     * Insert data ke tabel
     *
     * @param string $table Nama tabel
     * @param array $data Associative array [kolom => nilai]
     * @return int ID baris yang baru dimasukkan
     */
    public function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_map(fn($col) => "`{$col}`", array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO `{$table}` ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, array_values($data));

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Update data di tabel
     *
     * @param string $table Nama tabel
     * @param array $data Associative array [kolom => nilai] yang akan diupdate
     * @param string $where Klausa WHERE (gunakan placeholder ?)
     * @param array $whereParams Parameter untuk klausa WHERE
     * @return int Jumlah baris yang terpengaruh
     */
    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $setParts = array_map(fn($col) => "`{$col}` = ?", array_keys($data));
        $setClause = implode(', ', $setParts);

        $sql = "UPDATE `{$table}` SET {$setClause} WHERE {$where}";
        $params = array_merge(array_values($data), $whereParams);

        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Hapus data dari tabel
     *
     * @param string $table Nama tabel
     * @param string $where Klausa WHERE
     * @param array $params Parameter binding
     * @return int Jumlah baris yang dihapus
     */
    public function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM `{$table}` WHERE {$where}";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Hitung jumlah baris
     *
     * @param string $table Nama tabel
     * @param string $where Klausa WHERE (opsional)
     * @param array $params Parameter binding
     * @return int Jumlah baris
     */
    public function count(string $table, string $where = '1=1', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM `{$table}` WHERE {$where}";
        return (int) $this->fetchColumn($sql, $params);
    }

    /**
     * Mulai transaksi database
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit transaksi
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Rollback transaksi
     */
    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * Cek apakah sedang dalam transaksi
     */
    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * Mendapatkan ID terakhir yang di-insert
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }
}
