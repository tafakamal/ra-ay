<?php
/**
 * Konfigurasi Database
 * RA Attakal Yaqiin - Website Sekolah
 *
 * Mendukung konfigurasi dari file .env atau menggunakan nilai default.
 * Koneksi menggunakan PDO dengan prepared statements.
 */

declare(strict_types=1);

// Muat file .env jika ada
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Abaikan komentar
        $line = trim($line);
        if (str_starts_with($line, '#') || $line === '') {
            continue;
        }
        // Parse KEY=VALUE
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("{$key}={$value}");
            }
        }
    }
}

/**
 * Ambil nilai environment variable dengan fallback
 */
function env(string $key, mixed $default = null): mixed
{
    $value = $_ENV[$key] ?? getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }
    // Konversi tipe data untuk boolean
    return match (strtolower((string) $value)) {
        'true', '(true)' => true,
        'false', '(false)' => false,
        'null', '(null)' => null,
        default => $value,
    };
}

// Konfigurasi database
return [
    'driver'    => 'mysql',
    'host'      => env('DB_HOST', 'localhost'),
    'port'      => env('DB_PORT', '3306'),
    'database'  => env('DB_DATABASE', 'ra_attakal_yaqiin'),
    'username'  => env('DB_USERNAME', 'root'),
    'password'  => env('DB_PASSWORD', ''),
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'options'   => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_STRINGIFY_FETCHES  => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    ],
];
