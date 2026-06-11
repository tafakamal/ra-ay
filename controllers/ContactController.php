<?php
/**
 * ContactController
 * RA Attakal Yaqiin - Website Sekolah
 */

declare(strict_types=1);

class ContactController
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Halaman Hubungi Kami (Form Kontak & Informasi Kontak)
     */
    public function index(): void
    {
        $settingModel = new Setting();
        
        // Fetch contact-related settings
        $settings = $settingModel->getMultiple([
            'site_email', 
            'site_phone', 
            'site_whatsapp', 
            'site_address',
            'site_maps_embed', 
            'social_facebook', 
            'social_instagram',
            'social_youtube', 
            'social_tiktok'
        ]);

        // Get flash messages
        $flash = get_flash();

        // SEO Meta
        $pageTitle = 'Hubungi Kami';
        $pageDescription = 'Hubungi RA Attakal Yaqiin melalui formulir kontak, nomor telepon, WhatsApp, email, atau kunjungi alamat kami.';
        $pageKeywords = 'alamat ra attakal yaqiin, nomor telepon sekolah, email sekolah, hubungi tk islam';
        $currentPage = 'kontak';

        $breadcrumbs = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Hubungi Kami', 'url' => null]
        ];

        ob_start();
        require BASE_PATH . '/views/contact/index.php';
        $content = ob_get_clean();

        require BASE_PATH . '/views/layouts/main.php';
    }

    /**
     * Simpan Pesan Kontak Masuk
     */
    public function store(): void
    {
        // 1. Verifikasi CSRF Token
        if (!verify_csrf()) {
            flash('error', 'Token keamanan kedaluwarsa. Silakan muat ulang halaman dan coba lagi.');
            redirect('/kontak');
        }

        // 2. Simpan pesan menggunakan model Contact
        try {
            $contactModel = new Contact();
            $contactModel->create($_POST);
            
            flash('success', 'Alhamdulillah, pesan Anda telah terkirim. Kami akan menghubungi Anda segera.');
        } catch (InvalidArgumentException $e) {
            flash('error', $e->getMessage());
        } catch (RuntimeException $e) {
            // Rate limiting error
            flash('error', $e->getMessage());
        } catch (Exception $e) {
            flash('error', 'Terjadi kesalahan sistem saat mengirim pesan. Silakan hubungi kami via WhatsApp.');
        }

        redirect('/kontak');
    }
}
