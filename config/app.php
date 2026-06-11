<?php
/**
 * Konfigurasi Aplikasi
 * RA Attakal Yaqiin - Website Sekolah
 *
 * Mendefinisikan konstanta global dan fungsi helper yang digunakan
 * di seluruh aplikasi.
 */

declare(strict_types=1);

// Pastikan file database.php sudah dimuat (untuk fungsi env())
if (!function_exists('env')) {
    require_once __DIR__ . '/database.php';
}

// ============================================================
// Konstanta Aplikasi
// ============================================================

// Path dasar aplikasi
define('BASE_PATH', dirname(__DIR__));

// URL dasar website
define('BASE_URL', rtrim(env('APP_URL', 'http://localhost/RAAY'), '/'));

// Nama situs
define('SITE_NAME', env('SITE_NAME', 'RA Attakal Yaqiin'));

// Mode aplikasi (development / production)
define('APP_ENV', env('APP_ENV', 'development'));
define('APP_DEBUG', (bool) env('APP_DEBUG', true));

// Timezone
define('APP_TIMEZONE', env('APP_TIMEZONE', 'Asia/Jakarta'));
date_default_timezone_set(APP_TIMEZONE);

// Path upload
define('UPLOAD_PATH', BASE_PATH . '/uploads');
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB

// Path views
define('VIEWS_PATH', BASE_PATH . '/views');

// Konfigurasi session
define('SESSION_NAME', 'raay_session');
define('SESSION_LIFETIME', 7200); // 2 jam

// Versi aset (untuk cache busting)
define('ASSET_VERSION', '1.0.0');

// Warna tema
define('PRIMARY_COLOR', '#E8600A');
define('SECONDARY_COLOR', '#1E3A5F');

// Pagination
define('PER_PAGE', 12);
define('ADMIN_PER_PAGE', 20);

// ============================================================
// Fungsi Helper Global
// ============================================================

/**
 * Generate URL lengkap dari path relatif
 *
 * @param string $path Path relatif (contoh: '/berita/slug-artikel')
 * @return string URL lengkap
 */
function url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return $path ? BASE_URL . '/' . $path : BASE_URL;
}

/**
 * Generate URL untuk file aset statis (CSS, JS, gambar)
 * Menambahkan versi untuk cache busting
 *
 * @param string $path Path relatif ke file aset (contoh: 'css/style.css')
 * @return string URL aset dengan versi
 */
function asset(string $path): string
{
    $path = ltrim($path, '/');
    return BASE_URL . '/assets/' . $path . '?v=' . ASSET_VERSION;
}

/**
 * Generate URL untuk file yang diupload
 *
 * @param string|null $path Path relatif file upload
 * @param string $default Path default jika $path kosong
 * @return string URL file upload
 */
function upload_url(?string $path, string $default = 'assets/images/placeholder.jpg'): string
{
    if (empty($path)) {
        return BASE_URL . '/' . $default;
    }
    $path = ltrim($path, '/');
    return BASE_URL . '/' . $path;
}

/**
 * Escape output HTML untuk mencegah XSS
 *
 * @param string|null $value String yang akan di-escape
 * @return string String yang sudah di-escape
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Redirect ke URL tertentu
 *
 * @param string $path Path tujuan redirect
 * @param int $statusCode HTTP status code (default 302)
 */
function redirect(string $path, int $statusCode = 302): never
{
    $url = str_starts_with($path, 'http') ? $path : url($path);
    header("Location: {$url}", true, $statusCode);
    exit;
}

/**
 * Set pesan flash ke session
 *
 * @param string $type Tipe pesan: 'success', 'error', 'warning', 'info'
 * @param string $message Isi pesan
 */
function flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

/**
 * Ambil dan hapus pesan flash dari session
 *
 * @return array|null Data flash atau null jika tidak ada
 */
function get_flash(): ?array
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Cek apakah request saat ini adalah POST
 */
function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Cek apakah request saat ini adalah AJAX
 */
function is_ajax(): bool
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Ambil input dari request (GET/POST) dengan sanitasi dasar
 *
 * @param string $key Nama parameter
 * @param mixed $default Nilai default jika parameter tidak ada
 * @return mixed Nilai parameter
 */
function input(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

/**
 * Validasi CSRF token
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Generate hidden input field untuk CSRF token
 */
function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
}

/**
 * Verifikasi CSRF token
 */
function verify_csrf(): bool
{
    $token = $_POST['_token'] ?? '';
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Potong teks dengan panjang tertentu
 *
 * @param string $text Teks yang akan dipotong
 * @param int $length Panjang maksimal
 * @param string $suffix Akhiran (default: '...')
 * @return string Teks yang sudah dipotong
 */
function truncate(string $text, int $length = 150, string $suffix = '...'): string
{
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . $suffix;
}

/**
 * Generate slug dari judul (mendukung karakter Indonesia)
 *
 * @param string $text Teks yang akan dijadikan slug
 * @return string Slug yang dihasilkan
 */
function slugify(string $text): string
{
    // Konversi ke lowercase
    $text = mb_strtolower($text, 'UTF-8');

    // Ganti karakter khusus Indonesia (jika ada)
    $text = str_replace(
        ['é', 'è', 'ê', 'ë', 'à', 'â', 'ä', 'ù', 'û', 'ü', 'ô', 'ö', 'î', 'ï', 'ç'],
        ['e', 'e', 'e', 'e', 'a', 'a', 'a', 'u', 'u', 'u', 'o', 'o', 'i', 'i', 'c'],
        $text
    );

    // Hapus karakter non-alfanumerik (kecuali spasi dan strip)
    $text = preg_replace('/[^\w\s-]/u', '', $text);

    // Ganti spasi dan underscore dengan strip
    $text = preg_replace('/[\s_]+/', '-', $text);

    // Hapus strip berlebih
    $text = preg_replace('/-+/', '-', $text);

    // Hapus strip di awal dan akhir
    return trim($text, '-');
}

/**
 * Format tanggal ke format Indonesia
 *
 * @param string|null $date String tanggal
 * @param string $format Format output
 * @return string Tanggal terformat
 */
function format_date(?string $date, string $format = 'long'): string
{
    if (empty($date)) {
        return '-';
    }

    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return '-';
    }

    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $hari = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];

    return match ($format) {
        'short' => date('d', $timestamp) . ' ' . $bulan[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp),
        'long' => $hari[date('l', $timestamp)] . ', ' . date('d', $timestamp) . ' ' . $bulan[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp),
        'datetime' => $hari[date('l', $timestamp)] . ', ' . date('d', $timestamp) . ' ' . $bulan[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp) . ' ' . date('H:i', $timestamp) . ' WIB',
        'relative' => format_relative_date($timestamp),
        default => date($format, $timestamp),
    };
}

/**
 * Format tanggal relatif (misal: "2 jam yang lalu")
 */
function format_relative_date(int $timestamp): string
{
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'Baru saja';
    } elseif ($diff < 3600) {
        $menit = floor($diff / 60);
        return "{$menit} menit yang lalu";
    } elseif ($diff < 86400) {
        $jam = floor($diff / 3600);
        return "{$jam} jam yang lalu";
    } elseif ($diff < 604800) {
        $hari = floor($diff / 86400);
        return "{$hari} hari yang lalu";
    } else {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return date('d', $timestamp) . ' ' . $bulan[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp);
    }
}

/**
 * Dapatkan IP address pengunjung
 */
function get_client_ip(): string
{
    $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED',
             'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];

    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = explode(',', $_SERVER[$key])[0];
            $ip = trim($ip);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return '0.0.0.0';
}

/**
 * Cek apakah user sudah login
 */
function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

/**
 * Dapatkan data user yang sedang login
 */
function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

/**
 * Hanya izinkan akses jika sudah login, jika belum redirect ke login
 */
function require_auth(): void
{
    if (!is_logged_in()) {
        flash('error', 'Silakan login terlebih dahulu.');
        redirect('/admin/login');
    }
}

// ============================================================
// Error Handling
// ============================================================
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', BASE_PATH . '/storage/logs/error.log');
}
