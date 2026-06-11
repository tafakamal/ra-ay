<?php
/**
 * PpdbController
 * RA Attakal Yaqiin - Website Sekolah
 */

declare(strict_types=1);

class PpdbController
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Halaman Pendaftaran Siswa Baru (PPDB)
     */
    public function index(): void
    {
        $settingModel = new Setting();
        
        // Fetch PPDB settings
        $settings = $settingModel->getMultiple([
            'ppdb_status',
            'ppdb_start_date',
            'ppdb_end_date',
            'tahun_ajaran',
            'site_phone',
            'site_whatsapp'
        ]);

        $ppdbStatus = $settings['ppdb_status'] ?? 'closed';
        $startDate = $settings['ppdb_start_date'] ?? '';
        $endDate = $settings['ppdb_end_date'] ?? '';
        $tahunAjaran = $settings['tahun_ajaran'] ?? '2025/2026';

        // Check if current date is within range (if dates are defined)
        $isOpen = ($ppdbStatus === 'open');
        $today = date('Y-m-d');
        if ($isOpen && !empty($startDate) && !empty($endDate)) {
            $isOpen = ($today >= $startDate && $today <= $endDate);
        }

        // Get flash messages
        $flash = get_flash();

        // SEO Meta
        $pageTitle = 'Penerimaan Peserta Didik Baru (PPDB) ' . $tahunAjaran;
        $pageDescription = 'Pendaftaran Peserta Didik Baru (PPDB) online RA Attakal Yaqiin. Daftarkan putra-putri Anda sekarang untuk pendidikan anak usia dini berbasis Islam.';
        $pageKeywords = 'ppdb ra attakal yaqiin, daftar tk islam, pendaftaran paud tangerang selatan, tk islam ciputat';
        $currentPage = 'ppdb';

        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'PPDB Online', 'url' => null]
        ];

        ob_start();
        require BASE_PATH . '/views/ppdb/index.php';
        $content = ob_get_clean();

        require BASE_PATH . '/views/layouts/main.php';
    }

    /**
     * Simpan Pendaftaran PPDB Masuk
     */
    public function store(): void
    {
        // 1. Verifikasi CSRF Token
        if (!verify_csrf()) {
            flash('error', 'Token keamanan kedaluwarsa. Silakan muat ulang halaman dan coba lagi.');
            redirect('/ppdb');
        }

        $settingModel = new Setting();
        $settings = $settingModel->getMultiple(['ppdb_status', 'ppdb_start_date', 'ppdb_end_date']);
        
        $ppdbStatus = $settings['ppdb_status'] ?? 'closed';
        $startDate = $settings['ppdb_start_date'] ?? '';
        $endDate = $settings['ppdb_end_date'] ?? '';
        
        $isOpen = ($ppdbStatus === 'open');
        $today = date('Y-m-d');
        if ($isOpen && !empty($startDate) && !empty($endDate)) {
            $isOpen = ($today >= $startDate && $today <= $endDate);
        }

        // Jika PPDB ditutup, jangan ijinkan submit
        if (!$isOpen) {
            flash('error', 'Pendaftaran PPDB online saat ini sudah ditutup.');
            redirect('/ppdb');
        }

        // 2. Ambil & Validasi input
        $childName = trim($_POST['child_name'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        $birthPlace = trim($_POST['birth_place'] ?? '');
        $birthDate = trim($_POST['birth_date'] ?? '');
        $fatherName = trim($_POST['father_name'] ?? '');
        $motherName = trim($_POST['mother_name'] ?? '');
        $parentEmail = trim($_POST['parent_email'] ?? '');
        $parentPhone = trim($_POST['parent_phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        if (empty($childName) || empty($gender) || empty($birthPlace) || empty($birthDate) || 
            empty($fatherName) || empty($motherName) || empty($parentEmail) || empty($parentPhone) || empty($address)) {
            flash('error', 'Semua kolom yang bertanda bintang (*) wajib diisi.');
            redirect('/ppdb');
        }

        if (!filter_var($parentEmail, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Format email orang tua tidak valid.');
            redirect('/ppdb');
        }

        // 3. Format pesan registrasi agar tersimpan rapi di inbox kontak admin
        $formattedMessage = "=== PENDAFTARAN PPDB ONLINE ===\n\n"
                          . "DATA CALON SISWA:\n"
                          . "- Nama Lengkap: " . $childName . "\n"
                          . "- Jenis Kelamin: " . ($gender === 'L' ? 'Laki-laki' : 'Perempuan') . "\n"
                          . "- TTL: " . $birthPlace . ", " . $birthDate . "\n\n"
                          . "DATA ORANG TUA:\n"
                          . "- Nama Ayah: " . $fatherName . "\n"
                          . "- Nama Ibu: " . $motherName . "\n"
                          . "- Email Ortu: " . $parentEmail . "\n"
                          . "- Telepon/WA Ortu: " . $parentPhone . "\n"
                          . "- Alamat Rumah: " . $address . "\n\n"
                          . "INFORMASI TAMBAHAN / CATATAN:\n"
                          . ($notes !== '' ? $notes : '-') . "\n";

        try {
            $contactModel = new Contact();
            // Simpan pendaftaran ke tabel contacts
            $contactModel->create([
                'name' => $childName . ' (Ortu: ' . $motherName . ')',
                'email' => $parentEmail,
                'phone' => $parentPhone,
                'subject' => 'Pendaftaran PPDB: ' . $childName,
                'message' => $formattedMessage
            ]);

            flash('success', 'Alhamdulillah, pendaftaran online ananda ' . $childName . ' berhasil dikirim. Panitia PPDB akan segera menghubungi Anda melalui nomor telepon / WhatsApp yang terdaftar.');
        } catch (InvalidArgumentException $e) {
            flash('error', $e->getMessage());
        } catch (RuntimeException $e) {
            flash('error', $e->getMessage());
        } catch (Exception $e) {
            flash('error', 'Terjadi kesalahan sistem saat mengirim pendaftaran. Silakan hubungi kami via WhatsApp.');
        }

        redirect('/ppdb');
    }
}
