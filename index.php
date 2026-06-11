<?php
/**
 * Front Controller / Router
 * RA Attakal Yaqiin - Website Sekolah
 *
 * Semua request HTTP diarahkan ke file ini melalui .htaccess.
 * Router akan mencocokkan URL dengan route yang terdaftar
 * dan memanggil controller yang sesuai.
 */

declare(strict_types=1);

// ============================================================
// 1. Bootstrapping
// ============================================================

// Muat konfigurasi aplikasi (termasuk database config & helper functions)
require_once __DIR__ . '/config/app.php';

// Mulai session dengan konfigurasi yang aman
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path'     => '/',
        'domain'   => '',
        'secure'   => isset($_SERVER['HTTPS']),
        'httponly'  => true,
        'samesite'  => 'Lax',
    ]);
    session_start();
}

// Regenerasi session ID secara berkala (keamanan)
if (!isset($_SESSION['_last_regenerate'])) {
    $_SESSION['_last_regenerate'] = time();
} elseif (time() - $_SESSION['_last_regenerate'] > 300) { // 5 menit
    session_regenerate_id(true);
    $_SESSION['_last_regenerate'] = time();
}

// Autoload model
spl_autoload_register(function (string $className): void {
    $modelFile = BASE_PATH . '/models/' . $className . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
    }
});

// ============================================================
// 2. Parsing Request URI
// ============================================================

// Ambil path dari REQUEST_URI, hilangkan query string
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

// Hapus base path jika aplikasi tidak di root domain
if ($scriptName !== '/' && $scriptName !== '\\') {
    $requestUri = str_replace($scriptName, '', $requestUri);
}

// Parsing: hapus query string dan trailing slash
$path = parse_url($requestUri, PHP_URL_PATH) ?: '/';
$path = '/' . trim($path, '/');

// Method request
$method = $_SERVER['REQUEST_METHOD'];

// ============================================================
// 3. Definisi Routes
// ============================================================

/**
 * Daftar route yang tersedia
 * Format: [method => [pattern => [controller, action]]]
 *
 * Pattern mendukung placeholder:
 *   {slug}  -> menangkap segment URL (contoh: /berita/{slug})
 *   {id}    -> menangkap angka (contoh: /admin/news/{id})
 */
$routes = [
    'GET' => [
        // -- Halaman Utama --
        '/'                          => ['HomeController', 'index'],

        // -- Profil Sekolah --
        '/profil'                    => ['ProfileController', 'about'],
        '/profil/tentang'            => ['ProfileController', 'about'],
        '/profil/visi-misi'          => ['ProfileController', 'visionMission'],
        '/profil/guru'               => ['ProfileController', 'teachers'],
        '/profil/fasilitas'          => ['ProfileController', 'facilities'],

        // -- Akademik --
        '/akademik'                  => ['AcademicController', 'index'],
        '/akademik/kurikulum'        => ['AcademicController', 'curriculum'],
        '/akademik/program'          => ['AcademicController', 'programs'],
        '/akademik/program-unggulan' => ['AcademicController', 'programs'],

        // -- Berita --
        '/berita'                    => ['NewsController', 'index'],
        '/berita/cari'               => ['NewsController', 'search'],
        '/berita/kategori/{slug}'    => ['NewsController', 'category'],
        '/berita/{slug}'             => ['NewsController', 'detail'],

        // -- Galeri --
        '/galeri'                    => ['GalleryController', 'index'],

        // -- Kontak --
        '/kontak'                    => ['ContactController', 'index'],

        // -- PPDB --
        '/ppdb'                      => ['PpdbController', 'index'],

        // -- Admin Routes --
        '/admin'                     => ['AdminController', 'dashboard'],
        '/admin/login'               => ['AdminController', 'loginForm'],
        '/admin/logout'              => ['AdminController', 'logout'],

        // Admin - Berita
        '/admin/news'                => ['AdminNewsController', 'index'],
        '/admin/news/create'         => ['AdminNewsController', 'create'],
        '/admin/news/edit/{id}'      => ['AdminNewsController', 'edit'],
        '/admin/news/delete/{id}'    => ['AdminNewsController', 'delete'],

        // Admin - Kategori
        '/admin/categories'          => ['AdminCategoryController', 'index'],
        '/admin/categories/create'   => ['AdminCategoryController', 'create'],
        '/admin/categories/edit/{id}'   => ['AdminCategoryController', 'edit'],
        '/admin/categories/delete/{id}' => ['AdminCategoryController', 'delete'],

        // Admin - Halaman
        '/admin/pages'               => ['AdminPageController', 'index'],
        '/admin/pages/edit/{id}'     => ['AdminPageController', 'edit'],

        // Admin - Galeri
        '/admin/gallery'             => ['AdminGalleryController', 'index'],
        '/admin/gallery/create'      => ['AdminGalleryController', 'create'],
        '/admin/gallery/edit/{id}'   => ['AdminGalleryController', 'edit'],
        '/admin/gallery/delete/{id}' => ['AdminGalleryController', 'delete'],

        // Admin - Guru
        '/admin/teachers'            => ['AdminTeacherController', 'index'],
        '/admin/teachers/create'     => ['AdminTeacherController', 'create'],
        '/admin/teachers/edit/{id}'  => ['AdminTeacherController', 'edit'],
        '/admin/teachers/delete/{id}'=> ['AdminTeacherController', 'delete'],

        // Admin - Pesan Kontak
        '/admin/contacts'            => ['AdminContactController', 'index'],
        '/admin/contacts/view/{id}'  => ['AdminContactController', 'view'],
        '/admin/contacts/delete/{id}'=> ['AdminContactController', 'delete'],

        // Admin - Pengaturan
        '/admin/settings'            => ['AdminSettingController', 'index'],

        // Admin - Profil
        '/admin/profile'             => ['AdminProfileController', 'index'],
    ],

    'POST' => [
        // Kontak form submission
        '/kontak'                    => ['ContactController', 'store'],

        // PPDB form submission
        '/ppdb'                      => ['PpdbController', 'store'],

        // Admin - Login
        '/admin/login'               => ['AdminController', 'login'],

        // Admin - Berita
        '/admin/news/store'          => ['AdminNewsController', 'store'],
        '/admin/news/update/{id}'    => ['AdminNewsController', 'update'],

        // Admin - Kategori
        '/admin/categories/store'    => ['AdminCategoryController', 'store'],
        '/admin/categories/update/{id}' => ['AdminCategoryController', 'update'],

        // Admin - Halaman
        '/admin/pages/update/{id}'   => ['AdminPageController', 'update'],

        // Admin - Galeri
        '/admin/gallery/store'       => ['AdminGalleryController', 'store'],
        '/admin/gallery/update/{id}' => ['AdminGalleryController', 'update'],

        // Admin - Guru
        '/admin/teachers/store'      => ['AdminTeacherController', 'store'],
        '/admin/teachers/update/{id}'=> ['AdminTeacherController', 'update'],
        '/admin/teachers/reorder'    => ['AdminTeacherController', 'reorder'],

        // Admin - Pesan Kontak
        '/admin/contacts/mark-read/{id}'  => ['AdminContactController', 'markRead'],
        '/admin/contacts/mark-all-read'   => ['AdminContactController', 'markAllRead'],

        // Admin - Pengaturan
        '/admin/settings/update'     => ['AdminSettingController', 'update'],

        // Admin - Profil
        '/admin/profile/update'      => ['AdminProfileController', 'update'],
        '/admin/profile/password'    => ['AdminProfileController', 'changePassword'],
    ],
];

// ============================================================
// 4. Route Matching
// ============================================================

/**
 * Cocokkan path request dengan pattern route
 *
 * @param string $pattern Pattern route (bisa mengandung {slug}, {id})
 * @param string $path Path request
 * @return array|false Parameter yang tertangkap atau false jika tidak cocok
 */
function matchRoute(string $pattern, string $path): array|false
{
    // Route statis (tanpa placeholder)
    if ($pattern === $path) {
        return [];
    }

    // Konversi placeholder ke regex
    $regex = preg_replace(
        ['/\{id\}/', '/\{slug\}/'],
        ['(\d+)', '([a-zA-Z0-9\-]+)'],
        $pattern
    );

    $regex = '#^' . $regex . '$#';

    if (preg_match($regex, $path, $matches)) {
        array_shift($matches); // Hapus full match
        return $matches;
    }

    return false;
}

// Cari route yang cocok
$matched = false;
$controllerName = '';
$actionName = '';
$params = [];

$methodRoutes = $routes[$method] ?? [];

foreach ($methodRoutes as $pattern => $handler) {
    $result = matchRoute($pattern, $path);

    if ($result !== false) {
        [$controllerName, $actionName] = $handler;
        $params = $result;
        $matched = true;
        break;
    }
}

// ============================================================
// 5. Controller Loading & Execution
// ============================================================

if ($matched) {
    // Tentukan path file controller
    $controllerFile = BASE_PATH . '/controllers/' . $controllerName . '.php';

    if (!file_exists($controllerFile)) {
        // Controller belum dibuat - tampilkan pesan yang informatif
        if (APP_DEBUG) {
            http_response_code(500);
            echo "<h1>Controller Tidak Ditemukan</h1>";
            echo "<p>File controller <code>{$controllerName}.php</code> belum dibuat.</p>";
            echo "<p>Path yang diharapkan: <code>{$controllerFile}</code></p>";
            exit;
        }
        // Pada mode production, tampilkan 404
        showErrorPage(404);
        exit;
    }

    require_once $controllerFile;

    if (!class_exists($controllerName)) {
        if (APP_DEBUG) {
            http_response_code(500);
            echo "<h1>Class Tidak Ditemukan</h1>";
            echo "<p>Class <code>{$controllerName}</code> tidak ditemukan dalam file <code>{$controllerFile}</code></p>";
            exit;
        }
        showErrorPage(404);
        exit;
    }

    $controller = new $controllerName();

    if (!method_exists($controller, $actionName)) {
        if (APP_DEBUG) {
            http_response_code(500);
            echo "<h1>Method Tidak Ditemukan</h1>";
            echo "<p>Method <code>{$actionName}()</code> tidak ditemukan pada class <code>{$controllerName}</code></p>";
            exit;
        }
        showErrorPage(404);
        exit;
    }

    // Jalankan controller action dengan parameter
    try {
        call_user_func_array([$controller, $actionName], $params);
    } catch (\Throwable $e) {
        if (APP_DEBUG) {
            http_response_code(500);
            echo "<h1>Error</h1>";
            echo "<p><strong>" . e($e->getMessage()) . "</strong></p>";
            echo "<pre>" . e($e->getTraceAsString()) . "</pre>";
        } else {
            error_log("Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            showErrorPage(500);
        }
    }
} else {
    // Tidak ada route yang cocok -> 404
    showErrorPage(404);
}

// ============================================================
// 6. Halaman Error
// ============================================================

/**
 * Tampilkan halaman error (404, 500, dll)
 *
 * @param int $code HTTP status code
 */
function showErrorPage(int $code): void
{
    http_response_code($code);

    $errorViewFile = VIEWS_PATH . "/errors/{$code}.php";

    if (file_exists($errorViewFile)) {
        require_once $errorViewFile;
        return;
    }

    // Fallback: halaman error sederhana
    $title = match ($code) {
        404 => 'Halaman Tidak Ditemukan',
        403 => 'Akses Ditolak',
        500 => 'Kesalahan Server',
        default => 'Error',
    };

    $message = match ($code) {
        404 => 'Maaf, halaman yang Anda cari tidak ditemukan.',
        403 => 'Maaf, Anda tidak memiliki akses ke halaman ini.',
        500 => 'Maaf, terjadi kesalahan pada server. Silakan coba beberapa saat lagi.',
        default => 'Terjadi kesalahan.',
    };

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{$title} - RA Attakal Yaqiin</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #FFF7ED;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                color: #333;
            }
            .error-container {
                text-align: center;
                padding: 2rem;
                max-width: 500px;
            }
            .error-code {
                font-size: 8rem;
                font-weight: 800;
                color: #E8600A;
                line-height: 1;
                margin-bottom: 1rem;
            }
            .error-title {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
                color: #1E3A5F;
            }
            .error-message {
                font-size: 1rem;
                color: #666;
                margin-bottom: 2rem;
                line-height: 1.6;
            }
            .error-link {
                display: inline-block;
                padding: 0.75rem 2rem;
                background-color: #E8600A;
                color: white;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
                transition: background-color 0.3s;
            }
            .error-link:hover {
                background-color: #C74E08;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <div class="error-code">{$code}</div>
            <h1 class="error-title">{$title}</h1>
            <p class="error-message">{$message}</p>
            <a href="/" class="error-link">🏠 Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    HTML;
}
