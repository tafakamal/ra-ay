<?php
/**
 * Controller AdminSetting
 * RA Attakal Yaqiin
 */

declare(strict_types=1);

class AdminSettingController
{
    private Setting $settingModel;

    public function __construct()
    {
        require_auth();
        $this->settingModel = new Setting();
    }

    /**
     * Tampilkan form pengaturan (multi-tab)
     */
    public function index(): void
    {
        $title = 'Pengaturan Website';

        // Ambil semua pengaturan
        $settings = $this->settingModel->getAll();

        ob_start();
        require VIEWS_PATH . '/admin/settings/index.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/admin.php';
    }

    /**
     * Proses update pengaturan
     */
    public function update(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token keamanan CSRF tidak valid. Silakan coba lagi.');
            redirect('/admin/settings');
        }

        // Definisikan key settings beserta groupnya
        $settingKeys = [
            // General
            'site_name' => 'general',
            'tagline' => 'general',
            'description' => 'general',
            
            // Contact
            'email' => 'contact',
            'phone' => 'contact',
            'address' => 'contact',
            'map' => 'contact',
            
            // Social Media
            'facebook' => 'social',
            'instagram' => 'social',
            'youtube' => 'social',
            'tiktok' => 'social',
            'whatsapp' => 'social'
        ];

        try {
            $updatedCount = 0;
            foreach ($settingKeys as $key => $group) {
                // Ambil nilai dari POST
                $value = input($key);
                
                // Jika input ada (atau null jika kosong), simpan ke database
                if ($value !== null) {
                    $valueStr = trim((string)$value);
                    $this->settingModel->set($key, $valueStr, $group);
                    $updatedCount++;
                }
            }

            flash('success', 'Pengaturan website berhasil diperbarui.');
        } catch (\Exception $e) {
            flash('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage());
        }

        redirect('/admin/settings');
    }
}
