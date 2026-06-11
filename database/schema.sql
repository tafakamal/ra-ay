-- ============================================================
-- Database Schema untuk Website RA Attakal Yaqiin
-- Dibuat: 2026-06-11
-- Charset: UTF8MB4 untuk mendukung emoji dan karakter khusus
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS `ra_attakal_yaqiin`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `ra_attakal_yaqiin`;

-- ============================================================
-- Tabel: users (Pengguna / Admin)
-- ============================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'editor', 'author') NOT NULL DEFAULT 'author',
    `avatar` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `last_login` DATETIME DEFAULT NULL,
    `remember_token` VARCHAR(100) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `idx_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabel: settings (Pengaturan Situs)
-- ============================================================
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `setting_key` VARCHAR(100) NOT NULL,
    `setting_value` TEXT DEFAULT NULL,
    `setting_group` VARCHAR(50) NOT NULL DEFAULT 'general',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `idx_settings_key` (`setting_key`),
    INDEX `idx_settings_group` (`setting_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabel: categories (Kategori Berita)
-- ============================================================
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `color` VARCHAR(7) DEFAULT '#E8600A',
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `idx_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabel: news (Berita / Artikel)
-- ============================================================
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id` INT UNSIGNED DEFAULT NULL,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(280) NOT NULL,
    `excerpt` TEXT DEFAULT NULL,
    `content` LONGTEXT NOT NULL,
    `featured_image` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `views` INT UNSIGNED NOT NULL DEFAULT 0,
    `meta_title` VARCHAR(255) DEFAULT NULL,
    `meta_description` VARCHAR(300) DEFAULT NULL,
    `published_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `idx_news_slug` (`slug`),
    INDEX `idx_news_status` (`status`),
    INDEX `idx_news_published_at` (`published_at`),
    INDEX `idx_news_category` (`category_id`),
    INDEX `idx_news_featured` (`is_featured`, `status`),
    INDEX `idx_news_status_published` (`status`, `published_at`),
    CONSTRAINT `fk_news_category` FOREIGN KEY (`category_id`)
        REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_news_user` FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabel: pages (Halaman Statis)
-- ============================================================
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(280) NOT NULL,
    `content` LONGTEXT NOT NULL,
    `featured_image` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('draft', 'published') NOT NULL DEFAULT 'published',
    `sort_order` INT NOT NULL DEFAULT 0,
    `meta_title` VARCHAR(255) DEFAULT NULL,
    `meta_description` VARCHAR(300) DEFAULT NULL,
    `template` VARCHAR(50) DEFAULT 'default',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `idx_pages_slug` (`slug`),
    INDEX `idx_pages_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabel: gallery (Galeri Foto/Video)
-- ============================================================
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `thumbnail_path` VARCHAR(255) DEFAULT NULL,
    `media_type` ENUM('image', 'video') NOT NULL DEFAULT 'image',
    `album` VARCHAR(100) DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_gallery_album` (`album`),
    INDEX `idx_gallery_active` (`is_active`),
    INDEX `idx_gallery_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabel: teachers (Data Guru & Tenaga Pendidik)
-- ============================================================
DROP TABLE IF EXISTS `teachers`;
CREATE TABLE `teachers` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL,
    `nip` VARCHAR(30) DEFAULT NULL,
    `position` VARCHAR(100) NOT NULL,
    `education` VARCHAR(100) DEFAULT NULL,
    `photo` VARCHAR(255) DEFAULT NULL,
    `bio` TEXT DEFAULT NULL,
    `email` VARCHAR(150) DEFAULT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_teachers_active` (`is_active`),
    INDEX `idx_teachers_sort` (`sort_order`),
    INDEX `idx_teachers_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabel: contacts (Pesan Kontak dari Pengunjung)
-- ============================================================
DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `read_at` DATETIME DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_contacts_email` (`email`),
    INDEX `idx_contacts_read` (`is_read`),
    INDEX `idx_contacts_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- DATA AWAL (SEED DATA)
-- ============================================================

-- ------------------------------------------------------------
-- 1. Admin Default
-- Password: Admin@2026 (di-hash dengan password_hash PHP)
-- Untuk generate ulang: echo password_hash('Admin@2026', PASSWORD_BCRYPT);
-- ------------------------------------------------------------
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`) VALUES
('Administrator', 'admin@raattakalyaqiin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);

-- ------------------------------------------------------------
-- 2. Kategori Default
-- ------------------------------------------------------------
INSERT INTO `categories` (`name`, `slug`, `description`, `color`, `sort_order`) VALUES
('Kegiatan Sekolah', 'kegiatan-sekolah', 'Berbagai kegiatan dan acara yang diselenggarakan oleh RA Attakal Yaqiin', '#E8600A', 1),
('Pengumuman', 'pengumuman', 'Pengumuman resmi dari sekolah untuk orang tua dan wali murid', '#2563EB', 2),
('Artikel Islami', 'artikel-islami', 'Artikel-artikel islami untuk menambah wawasan keagamaan', '#059669', 3),
('Tips Parenting', 'tips-parenting', 'Tips dan panduan mendidik anak usia dini sesuai ajaran Islam', '#7C3AED', 4),
('Prestasi', 'prestasi', 'Prestasi dan pencapaian siswa serta sekolah', '#DC2626', 5);

-- ------------------------------------------------------------
-- 3. Pengaturan Default (Settings)
-- ------------------------------------------------------------
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
-- Umum
('site_name', 'RA Attakal Yaqiin', 'general'),
('site_tagline', 'Menanamkan Akhlak Mulia Sejak Dini', 'general'),
('site_description', 'Raudhatul Athfal (RA) Attakal Yaqiin adalah lembaga pendidikan anak usia dini berbasis Islam yang berkomitmen menanamkan akhlak mulia, cinta Al-Quran, dan karakter Islami sejak dini.', 'general'),
('site_keywords', 'RA Attakal Yaqiin, PAUD Islam, TK Islam, pendidikan anak usia dini, sekolah Islam, raudhatul athfal', 'general'),
('site_logo', 'uploads/logo.png', 'general'),
('site_favicon', 'uploads/favicon.ico', 'general'),

-- Kontak
('site_email', 'info@raattakalyaqiin.com', 'contact'),
('site_phone', '(021) 1234-5678', 'contact'),
('site_whatsapp', '6281234567890', 'contact'),
('site_address', 'Jl. Pendidikan No. 10, Kelurahan Cempaka, Kecamatan Ciputat, Kota Tangerang Selatan, Banten 15411', 'contact'),
('site_maps_embed', 'https://www.google.com/maps/embed?pb=example', 'contact'),

-- Media Sosial
('social_facebook', 'https://facebook.com/raattakalyaqiin', 'social'),
('social_instagram', 'https://instagram.com/raattakalyaqiin', 'social'),
('social_youtube', 'https://youtube.com/@raattakalyaqiin', 'social'),
('social_tiktok', 'https://tiktok.com/@raattakalyaqiin', 'social'),

-- Akademik
('tahun_ajaran', '2025/2026', 'academic'),
('ppdb_status', 'open', 'academic'),
('ppdb_start_date', '2026-01-15', 'academic'),
('ppdb_end_date', '2026-06-30', 'academic'),

-- Tampilan
('primary_color', '#E8600A', 'appearance'),
('secondary_color', '#1E3A5F', 'appearance'),
('hero_image', 'uploads/hero-banner.jpg', 'appearance'),
('footer_text', '© 2026 RA Attakal Yaqiin. Hak Cipta Dilindungi.', 'appearance');

-- ------------------------------------------------------------
-- 4. Halaman Statis Default (Pages)
-- ------------------------------------------------------------
INSERT INTO `pages` (`title`, `slug`, `content`, `status`, `sort_order`, `meta_title`, `meta_description`, `template`) VALUES
(
    'Tentang Sekolah',
    'tentang-sekolah',
    '<h2>Selamat Datang di RA Attakal Yaqiin</h2>
<p>Raudhatul Athfal (RA) Attakal Yaqiin merupakan lembaga pendidikan anak usia dini (PAUD) berbasis Islam yang berdiri sejak tahun 2010. Sekolah kami berkomitmen untuk membentuk generasi yang berakhlak mulia, cerdas, dan mencintai Al-Quran sejak usia dini.</p>

<h3>Sejarah Singkat</h3>
<p>RA Attakal Yaqiin didirikan atas dasar keprihatinan terhadap pendidikan anak usia dini yang kurang memperhatikan aspek keagamaan. Berawal dari sebuah majelis taklim kecil, para pendiri bertekad membangun lembaga pendidikan yang memadukan kurikulum nasional dengan nilai-nilai Islam.</p>

<h3>Identitas Sekolah</h3>
<ul>
    <li><strong>Nama:</strong> Raudhatul Athfal (RA) Attakal Yaqiin</li>
    <li><strong>NPSN:</strong> 69123456</li>
    <li><strong>Akreditasi:</strong> A (Unggul)</li>
    <li><strong>Tahun Berdiri:</strong> 2010</li>
    <li><strong>Status:</strong> Swasta</li>
    <li><strong>Jam Operasional:</strong> Senin - Jumat, 07.00 - 11.30 WIB</li>
</ul>

<h3>Mengapa Memilih RA Attakal Yaqiin?</h3>
<p>Kami percaya bahwa setiap anak adalah amanah dari Allah SWT. Oleh karena itu, kami berkomitmen untuk memberikan pendidikan terbaik yang seimbang antara ilmu duniawi dan ukhrawi. Dengan tenaga pengajar yang berpengalaman dan lingkungan belajar yang kondusif, kami siap menjadi mitra terbaik orang tua dalam mendidik putra-putri tercinta.</p>',
    'published',
    1,
    'Tentang Sekolah - RA Attakal Yaqiin',
    'RA Attakal Yaqiin adalah lembaga pendidikan anak usia dini berbasis Islam yang berkomitmen membentuk generasi berakhlak mulia.',
    'default'
),
(
    'Visi & Misi',
    'visi-misi',
    '<h2>Visi</h2>
<p class="lead">Menjadi Lembaga Pendidikan Anak Usia Dini Unggulan yang Menghasilkan Generasi Qurani, Berakhlak Mulia, Cerdas, dan Kreatif.</p>

<h2>Misi</h2>
<ol>
    <li>Menanamkan nilai-nilai keimanan dan ketakwaan kepada Allah SWT sejak usia dini melalui pembiasaan ibadah dan akhlak mulia.</li>
    <li>Menyelenggarakan pembelajaran yang aktif, kreatif, efektif, dan menyenangkan (PAKEM) sesuai tahap perkembangan anak.</li>
    <li>Mengembangkan potensi kecerdasan majemuk (multiple intelligences) setiap peserta didik secara optimal.</li>
    <li>Membiasakan anak mencintai Al-Quran melalui program tahfidz dan tahsin yang terstruktur.</li>
    <li>Membangun kemitraan yang harmonis antara sekolah, orang tua, dan masyarakat dalam mendidik anak.</li>
    <li>Menyediakan lingkungan belajar yang aman, nyaman, dan stimulatif bagi tumbuh kembang anak.</li>
    <li>Mengembangkan kreativitas dan kemandirian anak melalui berbagai kegiatan ekstrakurikuler.</li>
</ol>

<h2>Tujuan</h2>
<ul>
    <li>Terbentuknya peserta didik yang hafal Juz 30 dan mampu membaca Al-Quran dengan baik.</li>
    <li>Terbentuknya peserta didik yang memiliki akhlak mulia dan adab Islami dalam kehidupan sehari-hari.</li>
    <li>Terbentuknya peserta didik yang siap melanjutkan ke jenjang pendidikan dasar (SD/MI).</li>
    <li>Terciptanya lingkungan sekolah yang Islami, ramah anak, dan kondusif untuk belajar.</li>
</ul>

<h2>Motto</h2>
<p class="lead text-center"><em>"Bermain, Belajar, dan Berakhlak Mulia"</em></p>',
    'published',
    2,
    'Visi & Misi - RA Attakal Yaqiin',
    'Visi RA Attakal Yaqiin: Menjadi lembaga PAUD unggulan yang menghasilkan generasi Qurani, berakhlak mulia, cerdas, dan kreatif.',
    'default'
),
(
    'Kurikulum',
    'kurikulum',
    '<h2>Kurikulum RA Attakal Yaqiin</h2>
<p>RA Attakal Yaqiin menerapkan kurikulum terpadu yang memadukan <strong>Kurikulum Merdeka Belajar</strong> dari Kementerian Pendidikan dan Kebudayaan dengan <strong>Kurikulum Kemenag</strong> serta muatan lokal berbasis keislaman.</p>

<h3>Struktur Kurikulum</h3>

<h4>1. Pengembangan Nilai Agama dan Moral</h4>
<ul>
    <li>Pembiasaan sholat dhuha berjamaah</li>
    <li>Hafalan doa harian dan hadits pilihan</li>
    <li>Kisah-kisah Nabi dan Rasul</li>
    <li>Adab dan akhlak Islami</li>
</ul>

<h4>2. Pengembangan Fisik Motorik</h4>
<ul>
    <li>Motorik kasar: senam, olahraga, dan permainan outdoor</li>
    <li>Motorik halus: mewarnai, menggunting, menempel, dan menulis</li>
</ul>

<h4>3. Pengembangan Kognitif</h4>
<ul>
    <li>Pengenalan angka dan berhitung</li>
    <li>Pengenalan huruf dan membaca</li>
    <li>Sains sederhana dan eksplorasi alam</li>
</ul>

<h4>4. Pengembangan Bahasa</h4>
<ul>
    <li>Bahasa Indonesia</li>
    <li>Bahasa Arab dasar</li>
    <li>Bahasa Inggris dasar</li>
</ul>

<h4>5. Pengembangan Sosial Emosional</h4>
<ul>
    <li>Kerja sama dan gotong royong</li>
    <li>Kemandirian dan tanggung jawab</li>
    <li>Pengelolaan emosi</li>
</ul>

<h4>6. Pengembangan Seni</h4>
<ul>
    <li>Seni musik dan nasyid</li>
    <li>Seni rupa dan kerajinan tangan</li>
    <li>Seni tari dan gerak</li>
</ul>

<h3>Program Tahfidz Al-Quran</h3>
<p>Program unggulan kami dalam bidang tahfidz Al-Quran dengan target hafalan Juz 30 selama 2 tahun pembelajaran. Metode yang digunakan adalah metode <strong>Talaqqi</strong> dan <strong>Muraja\'ah</strong> yang terbukti efektif untuk anak usia dini.</p>',
    'published',
    3,
    'Kurikulum - RA Attakal Yaqiin',
    'Kurikulum RA Attakal Yaqiin memadukan Kurikulum Merdeka dengan muatan keislaman untuk pendidikan anak usia dini yang komprehensif.',
    'default'
),
(
    'Program Unggulan',
    'program-unggulan',
    '<h2>Program Unggulan RA Attakal Yaqiin</h2>
<p>RA Attakal Yaqiin menyediakan berbagai program unggulan yang dirancang khusus untuk mengoptimalkan tumbuh kembang anak secara holistik.</p>

<h3>🕌 1. Program Tahfidz Quran</h3>
<p>Program unggulan utama kami. Peserta didik dibimbing untuk menghafal Al-Quran Juz 30 dengan metode Talaqqi yang menyenangkan. Setiap hari anak-anak mendapatkan sesi tahfidz selama 30 menit dengan guru tahfidz yang bersertifikat.</p>

<h3>📚 2. Program Literasi Dini</h3>
<p>Program membaca dan menulis yang terstruktur menggunakan metode fonik (phonics) yang disesuaikan untuk anak Indonesia. Target: anak mampu membaca kalimat sederhana sebelum lulus dari RA.</p>

<h3>🔬 3. Sains Cilik (Little Scientist)</h3>
<p>Program eksplorasi sains sederhana yang mengajarkan anak untuk mengamati, bertanya, dan bereksperimen. Kegiatan meliputi percobaan sederhana, berkebun, dan mengamati alam sekitar.</p>

<h3>🎨 4. Kreativitas & Seni Islami</h3>
<p>Program pengembangan kreativitas melalui seni kaligrafi dasar, kerajinan tangan bertema Islami, nasyid, dan seni pertunjukan.</p>

<h3>🏊 5. Ekskul Olahraga & Renang</h3>
<p>Kegiatan ekstrakurikuler olahraga termasuk renang, senam, dan permainan tradisional yang bertujuan mengembangkan motorik kasar anak.</p>

<h3>💻 6. Pengenalan Teknologi</h3>
<p>Program pengenalan teknologi dasar yang sesuai usia, menggunakan media interaktif edukatif dengan konten Islami.</p>

<h3>🌱 7. Program Parenting</h3>
<p>Program khusus untuk orang tua berupa seminar, workshop, dan konsultasi parenting Islami secara berkala. Membangun sinergi antara pendidikan di sekolah dan di rumah.</p>',
    'published',
    4,
    'Program Unggulan - RA Attakal Yaqiin',
    'Program unggulan RA Attakal Yaqiin: Tahfidz Quran, Literasi Dini, Sains Cilik, Kreativitas Islami, dan program lainnya.',
    'default'
),
(
    'Fasilitas',
    'fasilitas',
    '<h2>Fasilitas RA Attakal Yaqiin</h2>
<p>RA Attakal Yaqiin menyediakan fasilitas yang lengkap dan memadai untuk menunjang proses pembelajaran yang optimal dan nyaman bagi peserta didik.</p>

<h3>Fasilitas Utama</h3>
<ul>
    <li><strong>Ruang Kelas Ber-AC:</strong> 6 ruang kelas yang nyaman, dilengkapi dengan AC, meja-kursi sesuai ukuran anak, dan media pembelajaran interaktif.</li>
    <li><strong>Musholla:</strong> Musholla mini untuk pembiasaan ibadah dan kegiatan keagamaan anak.</li>
    <li><strong>Perpustakaan:</strong> Perpustakaan mini dengan koleksi buku cerita Islami, ensiklopedia anak, dan buku bergambar.</li>
    <li><strong>Ruang Bermain Indoor:</strong> Area bermain dalam ruangan yang aman dengan berbagai permainan edukatif.</li>
    <li><strong>Playground Outdoor:</strong> Taman bermain luar ruangan dengan ayunan, perosotan, jungkat-jungkit, dan area pasir.</li>
    <li><strong>Ruang Seni & Kreativitas:</strong> Ruang khusus untuk kegiatan seni rupa, kerajinan tangan, dan musik.</li>
    <li><strong>Aula Serbaguna:</strong> Aula untuk acara besar seperti wisuda, pentas seni, dan pertemuan orang tua.</li>
    <li><strong>UKS (Unit Kesehatan Sekolah):</strong> Ruang kesehatan dengan perlengkapan P3K dasar.</li>
    <li><strong>Dapur Bersih:</strong> Dapur yang higienis untuk menyiapkan makan siang dan snack sehat anak.</li>
    <li><strong>Kamar Mandi Ramah Anak:</strong> Kamar mandi yang bersih, aman, dan sesuai ukuran anak.</li>
    <li><strong>Area Parkir:</strong> Lahan parkir yang luas untuk kenyamanan antar-jemput peserta didik.</li>
    <li><strong>CCTV:</strong> Sistem keamanan CCTV 24 jam di seluruh area sekolah.</li>
    <li><strong>Taman Hijau:</strong> Area hijau untuk kegiatan berkebun dan mengenal alam.</li>
</ul>

<h3>Keamanan & Kenyamanan</h3>
<p>Keamanan dan kenyamanan peserta didik adalah prioritas utama kami. Seluruh area sekolah dilengkapi dengan CCTV, pagar pengaman, dan petugas keamanan. Seluruh lantai menggunakan material anti-slip dan sudut-sudut ruangan dilapisi pelindung untuk mencegah cedera.</p>',
    'published',
    5,
    'Fasilitas - RA Attakal Yaqiin',
    'Fasilitas lengkap RA Attakal Yaqiin: ruang kelas ber-AC, musholla, perpustakaan, playground, dan fasilitas pendukung lainnya.',
    'default'
);

-- ------------------------------------------------------------
-- 5. Berita / Artikel Contoh
-- ------------------------------------------------------------
INSERT INTO `news` (`category_id`, `user_id`, `title`, `slug`, `excerpt`, `content`, `status`, `is_featured`, `views`, `meta_title`, `meta_description`, `published_at`) VALUES
(
    1, 1,
    'Keseruan Manasik Haji Cilik RA Attakal Yaqiin Tahun 2026',
    'keseruan-manasik-haji-cilik-ra-attakal-yaqiin-tahun-2026',
    'Ratusan siswa RA Attakal Yaqiin mengikuti kegiatan manasik haji cilik yang diselenggarakan di halaman sekolah. Kegiatan ini bertujuan mengenalkan rukun haji sejak dini.',
    '<p>Alhamdulillah, pada hari Sabtu tanggal 7 Juni 2026, RA Attakal Yaqiin menyelenggarakan kegiatan <strong>Manasik Haji Cilik</strong> yang diikuti oleh seluruh peserta didik kelompok A dan B.</p>

<p>Kegiatan yang berlangsung meriah ini bertujuan untuk mengenalkan rukun dan tata cara ibadah haji kepada anak-anak sejak usia dini. Para siswa mengenakan pakaian ihram putih dan mengikuti serangkaian kegiatan yang menyimulasikan ibadah haji, mulai dari tawaf, sa\'i, hingga lempar jumrah.</p>

<h3>Rangkaian Kegiatan</h3>
<ol>
    <li><strong>Ihram dan Niat:</strong> Anak-anak diajarkan cara berpakaian ihram dan mengucapkan niat haji.</li>
    <li><strong>Tawaf:</strong> Mengelilingi miniatur Ka\'bah sebanyak 7 kali putaran.</li>
    <li><strong>Sa\'i:</strong> Berjalan bolak-balik antara bukit Shafa dan Marwah.</li>
    <li><strong>Wukuf di Arafah:</strong> Berdoa dan berdzikir bersama.</li>
    <li><strong>Lempar Jumrah:</strong> Melempar batu ke arah tiang jumrah.</li>
</ol>

<p>Kepala Sekolah, Ustadzah Hj. Siti Aminah, S.Pd.I, menyampaikan bahwa kegiatan ini merupakan salah satu program tahunan yang sangat penting. "Melalui kegiatan ini, kami berharap anak-anak dapat mengenal dan mencintai ibadah haji sejak dini. Ini adalah bagian dari upaya kami menanamkan nilai-nilai keislaman," ujarnya.</p>

<p>Kegiatan ini juga dihadiri oleh para orang tua yang turut mendampingi putra-putrinya. Antusiasme anak-anak yang tinggi membuat kegiatan ini berjalan dengan sangat meriah dan penuh kebahagiaan.</p>',
    'published', 1, 245,
    'Manasik Haji Cilik RA Attakal Yaqiin 2026',
    'Keseruan kegiatan manasik haji cilik di RA Attakal Yaqiin yang diikuti ratusan siswa untuk mengenalkan ibadah haji sejak dini.',
    '2026-06-07 08:00:00'
),
(
    2, 1,
    'Pendaftaran Peserta Didik Baru (PPDB) Tahun Ajaran 2026/2027 Dibuka',
    'ppdb-tahun-ajaran-2026-2027-dibuka',
    'RA Attakal Yaqiin membuka pendaftaran peserta didik baru untuk tahun ajaran 2026/2027. Segera daftarkan putra-putri Anda!',
    '<p>Bismillahirrahmanirrahim,</p>

<p>RA Attakal Yaqiin dengan senang hati mengumumkan bahwa <strong>Pendaftaran Peserta Didik Baru (PPDB) Tahun Ajaran 2026/2027</strong> telah resmi dibuka!</p>

<h3>Persyaratan Pendaftaran</h3>
<ul>
    <li>Usia minimal 4 tahun (Kelompok A) atau 5 tahun (Kelompok B) per Juli 2026</li>
    <li>Fotokopi Akta Kelahiran (2 lembar)</li>
    <li>Fotokopi Kartu Keluarga (2 lembar)</li>
    <li>Pas foto ukuran 3x4 (4 lembar, latar merah)</li>
    <li>Fotokopi KTP kedua orang tua</li>
    <li>Mengisi formulir pendaftaran</li>
</ul>

<h3>Jadwal Pendaftaran</h3>
<ul>
    <li><strong>Gelombang 1:</strong> 15 Januari - 28 Februari 2026</li>
    <li><strong>Gelombang 2:</strong> 1 Maret - 30 April 2026</li>
    <li><strong>Gelombang 3:</strong> 1 Mei - 30 Juni 2026 (jika kuota masih tersedia)</li>
</ul>

<h3>Biaya Pendaftaran</h3>
<p>Biaya formulir pendaftaran: <strong>Rp 100.000,-</strong></p>
<p>Informasi lengkap mengenai biaya pendidikan dapat diperoleh di kantor administrasi sekolah.</p>

<h3>Keunggulan RA Attakal Yaqiin</h3>
<ul>
    <li>Program Tahfidz Al-Quran Juz 30</li>
    <li>Kurikulum Merdeka + Muatan Keislaman</li>
    <li>Tenaga pengajar bersertifikat</li>
    <li>Fasilitas lengkap dan ramah anak</li>
    <li>Kelas kecil (maksimal 20 siswa per kelas)</li>
</ul>

<p>Untuk informasi lebih lanjut, silakan hubungi:</p>
<p>📞 (021) 1234-5678 | 📱 WA: 0812-3456-7890</p>
<p>📍 Jl. Pendidikan No. 10, Ciputat, Tangerang Selatan</p>',
    'published', 1, 523,
    'PPDB RA Attakal Yaqiin 2026/2027',
    'Pendaftaran peserta didik baru RA Attakal Yaqiin tahun ajaran 2026/2027 telah dibuka. Persyaratan, jadwal, dan informasi lengkap.',
    '2026-01-10 09:00:00'
),
(
    3, 1,
    'Keutamaan Mengajarkan Al-Quran kepada Anak Sejak Dini',
    'keutamaan-mengajarkan-al-quran-kepada-anak-sejak-dini',
    'Mengajarkan Al-Quran kepada anak sejak usia dini memiliki banyak keutamaan. Berikut dalil-dalil dan manfaat memperkenalkan Al-Quran kepada anak.',
    '<p>Islam sangat menganjurkan umatnya untuk mengajarkan Al-Quran kepada anak-anak sejak usia dini. Rasulullah SAW bersabda:</p>

<blockquote>
    <p><em>"Sebaik-baik kalian adalah orang yang mempelajari Al-Quran dan mengajarkannya."</em> (HR. Bukhari)</p>
</blockquote>

<h3>Keutamaan Mengajarkan Al-Quran</h3>

<h4>1. Membentuk Karakter Qurani</h4>
<p>Anak yang terbiasa mendengar dan mempelajari Al-Quran sejak kecil akan tumbuh dengan karakter yang sesuai dengan nilai-nilai Al-Quran. Akhlak mulia, kejujuran, dan kasih sayang akan tertanam kuat dalam jiwa mereka.</p>

<h4>2. Meningkatkan Kecerdasan Otak</h4>
<p>Penelitian ilmiah menunjukkan bahwa menghafal Al-Quran dapat meningkatkan daya ingat dan konsentrasi anak. Proses menghafal merangsang perkembangan otak kanan dan kiri secara seimbang.</p>

<h4>3. Menjadi Mahkota bagi Orang Tua</h4>
<p>Rasulullah SAW bersabda: <em>"Barangsiapa membaca Al-Quran, mempelajarinya, dan mengamalkannya, maka dipakaikan pada hari kiamat mahkota dari cahaya, cahayanya bagaikan cahaya matahari."</em> (HR. Hakim)</p>

<h4>4. Golden Age (Usia Emas)</h4>
<p>Usia 0-6 tahun adalah masa emas perkembangan otak anak. Pada masa ini, kemampuan anak untuk menyerap dan menghafal sangat tinggi. Inilah waktu terbaik untuk memperkenalkan Al-Quran.</p>

<h3>Tips Mengajarkan Al-Quran pada Anak</h3>
<ol>
    <li>Mulai dengan memperdengarkan murattal sejak bayi</li>
    <li>Gunakan metode yang menyenangkan dan tidak memaksa</li>
    <li>Buat jadwal rutin dengan durasi yang singkat</li>
    <li>Berikan apresiasi dan motivasi</li>
    <li>Orang tua menjadi teladan dalam membaca Al-Quran</li>
</ol>

<p>Di RA Attakal Yaqiin, kami memiliki program tahfidz khusus yang dirancang sesuai kemampuan anak usia dini. Dengan metode Talaqqi yang menyenangkan, anak-anak dapat menghafal Juz 30 dalam 2 tahun.</p>',
    'published', 0, 189,
    'Keutamaan Mengajarkan Al-Quran kepada Anak',
    'Keutamaan dan manfaat mengajarkan Al-Quran kepada anak sejak usia dini beserta tips praktisnya.',
    '2026-05-20 10:00:00'
),
(
    4, 1,
    'Tips Mendidik Anak Usia Dini agar Mandiri dan Percaya Diri',
    'tips-mendidik-anak-usia-dini-agar-mandiri-dan-percaya-diri',
    'Kemandirian dan rasa percaya diri adalah bekal penting bagi anak. Simak tips praktis mendidik anak agar tumbuh mandiri dan percaya diri.',
    '<p>Mendidik anak agar mandiri dan percaya diri sejak usia dini merupakan investasi terbaik untuk masa depan mereka. Berikut adalah tips yang bisa diterapkan oleh para orang tua.</p>

<h3>1. Berikan Kesempatan untuk Mencoba</h3>
<p>Jangan terlalu sering membantu anak dalam segala hal. Biarkan mereka mencoba sendiri, meskipun hasilnya belum sempurna. Misalnya, biarkan anak memakai sepatu sendiri, makan sendiri, atau merapikan mainannya.</p>

<h3>2. Beri Pujian yang Spesifik</h3>
<p>Alih-alih mengatakan "Kamu pintar!", cobalah memberikan pujian yang lebih spesifik seperti "Wah, kamu hebat sudah bisa memakai sepatu sendiri!" Pujian spesifik membuat anak memahami apa yang mereka lakukan dengan baik.</p>

<h3>3. Buat Rutinitas yang Konsisten</h3>
<p>Anak-anak merasa aman dan percaya diri ketika memiliki rutinitas yang jelas. Buatlah jadwal harian yang konsisten untuk waktu makan, bermain, belajar, dan tidur.</p>

<h3>4. Ajak Anak Mengambil Keputusan Sederhana</h3>
<p>Libatkan anak dalam pengambilan keputusan sederhana, seperti memilih baju yang akan dipakai atau memilih menu makan siang. Ini melatih kemampuan mereka dalam mengambil keputusan.</p>

<h3>5. Jangan Bandingkan dengan Anak Lain</h3>
<p>Setiap anak memiliki kecepatan perkembangan yang berbeda. Membandingkan anak dengan teman sebayanya hanya akan menurunkan rasa percaya diri mereka.</p>

<h3>6. Berikan Tanggung Jawab Sesuai Usia</h3>
<p>Berikan tugas-tugas kecil yang sesuai usia, seperti menyimpan sepatu di rak, membuang sampah pada tempatnya, atau membantu menyiram tanaman.</p>

<h3>7. Jadilah Pendengar yang Baik</h3>
<p>Dengarkan cerita dan keluhan anak dengan penuh perhatian. Anak yang merasa didengar akan tumbuh menjadi pribadi yang percaya diri dan memiliki kemampuan komunikasi yang baik.</p>

<p><em>Semoga tips ini bermanfaat untuk para orang tua dalam mendidik putra-putri tercinta. Ingatlah bahwa setiap anak adalah unik dan istimewa.</em></p>',
    'published', 0, 312,
    'Tips Mendidik Anak Mandiri dan Percaya Diri',
    'Tips praktis mendidik anak usia dini agar tumbuh mandiri dan percaya diri sejak kecil.',
    '2026-05-15 14:00:00'
),
(
    5, 1,
    'Siswa RA Attakal Yaqiin Raih Juara 1 Lomba Tahfidz Se-Kota Tangerang Selatan',
    'siswa-ra-attakal-yaqiin-juara-1-lomba-tahfidz-se-tangsel',
    'Membanggakan! Ananda Khalisa Azzahra, siswi RA Attakal Yaqiin, berhasil meraih juara 1 dalam Lomba Tahfidz Quran tingkat Kota Tangerang Selatan.',
    '<p>Alhamdulillah, kabar membanggakan datang dari <strong>Ananda Khalisa Azzahra</strong>, siswi Kelompok B RA Attakal Yaqiin, yang berhasil meraih <strong>Juara 1 Lomba Tahfidz Quran Tingkat Kota Tangerang Selatan</strong> yang diselenggarakan pada tanggal 1-2 Juni 2026.</p>

<p>Lomba yang diikuti oleh lebih dari 150 peserta dari berbagai RA/TK se-Kota Tangerang Selatan ini diselenggarakan oleh Dinas Pendidikan Kota Tangerang Selatan bekerja sama dengan Forum Komunikasi RA/BA/TA.</p>

<h3>Profil Juara</h3>
<ul>
    <li><strong>Nama:</strong> Khalisa Azzahra</li>
    <li><strong>Kelas:</strong> Kelompok B2</li>
    <li><strong>Hafalan:</strong> 2 Juz (Juz 29 & 30)</li>
    <li><strong>Guru Pembimbing:</strong> Ustadzah Nurhalimah, S.Pd.I</li>
</ul>

<p>Kepala Sekolah RA Attakal Yaqiin, Ustadzah Hj. Siti Aminah, S.Pd.I, menyampaikan rasa syukur dan bangga atas pencapaian ini. "Prestasi Ananda Khalisa adalah buah dari ketekunan, doa orang tua, dan bimbingan guru-guru kami. Semoga ini menjadi motivasi bagi seluruh siswa RA Attakal Yaqiin untuk terus semangat menghafal Al-Quran," ujarnya.</p>

<p>Selain Khalisa, beberapa siswa RA Attakal Yaqiin juga berhasil masuk 10 besar dalam berbagai kategori lomba yang diselenggarakan dalam event yang sama, termasuk lomba adzan, lomba hafalan doa harian, dan lomba mewarnai kaligrafi.</p>

<p>Selamat kepada Ananda Khalisa Azzahra dan seluruh siswa-siswi RA Attakal Yaqiin! Terus ukir prestasi untuk kebanggaan sekolah dan orang tua. 🏆</p>',
    'published', 1, 478,
    'Siswa RA Attakal Yaqiin Juara 1 Lomba Tahfidz',
    'Siswa RA Attakal Yaqiin meraih juara 1 lomba tahfidz Quran tingkat Kota Tangerang Selatan tahun 2026.',
    '2026-06-03 11:00:00'
);

-- ------------------------------------------------------------
-- 6. Data Guru & Tenaga Pendidik
-- ------------------------------------------------------------
INSERT INTO `teachers` (`name`, `nip`, `position`, `education`, `photo`, `bio`, `email`, `sort_order`, `is_active`) VALUES
('Hj. Siti Aminah, S.Pd.I', '197503152003122001', 'Kepala Sekolah', 'S1 Pendidikan Islam Anak Usia Dini - UIN Jakarta', NULL, 'Berpengalaman lebih dari 20 tahun dalam pendidikan anak usia dini. Aktif dalam berbagai pelatihan dan seminar pendidikan PAUD tingkat nasional.', 'siti.aminah@raattakalyaqiin.com', 1, 1),
('Nurhalimah, S.Pd.I', '198201202006042002', 'Guru Kelas B1 & Koordinator Tahfidz', 'S1 Pendidikan Agama Islam - UIN Jakarta', NULL, 'Guru tahfidz bersertifikat dengan hafalan 30 Juz. Menggunakan metode Talaqqi yang efektif untuk anak usia dini.', 'nurhalimah@raattakalyaqiin.com', 2, 1),
('Dewi Rahmawati, S.Pd', NULL, 'Guru Kelas A1', 'S1 Pendidikan Guru PAUD - Universitas Negeri Jakarta', NULL, 'Kreatif dalam merancang kegiatan pembelajaran yang menyenangkan. Memiliki sertifikasi guru PAUD profesional.', 'dewi.rahmawati@raattakalyaqiin.com', 3, 1),
('Fatimah Az-Zahra, S.Pd.I', NULL, 'Guru Kelas A2', 'S1 Pendidikan Islam Anak Usia Dini - STAI Al-Hikmah', NULL, 'Spesialis dalam pengembangan karakter dan akhlak anak usia dini dengan pendekatan Islami.', 'fatimah@raattakalyaqiin.com', 4, 1),
('Aisyah Putri, S.Pd', NULL, 'Guru Kelas B2', 'S1 Pendidikan Guru PAUD - Universitas Muhammadiyah Jakarta', NULL, 'Fokus pada pengembangan literasi dan numerasi anak usia dini. Aktif mengikuti pelatihan Kurikulum Merdeka.', 'aisyah@raattakalyaqiin.com', 5, 1),
('Ustadz Ahmad Fauzi, S.Ag', NULL, 'Guru PAI & Tahsin', 'S1 Pendidikan Agama Islam - UIN Banten', NULL, 'Mengajarkan pendidikan agama Islam dan tahsin (perbaikan bacaan Al-Quran) dengan metode yang ramah anak.', 'ahmad.fauzi@raattakalyaqiin.com', 6, 1),
('Rina Fitriani, A.Md', NULL, 'Guru Pendamping & Tata Usaha', 'D3 Administrasi Pendidikan - Universitas Terbuka', NULL, 'Bertanggung jawab dalam pendampingan kelas dan administrasi sekolah. Teliti dan terorganisir.', 'rina@raattakalyaqiin.com', 7, 1),
('Budi Santoso', NULL, 'Petugas Keamanan & Kebersihan', 'SMA', NULL, 'Menjaga keamanan dan kebersihan lingkungan sekolah agar selalu aman dan nyaman untuk anak-anak.', NULL, 8, 1);

-- ------------------------------------------------------------
-- 7. Data Galeri Contoh
-- ------------------------------------------------------------
INSERT INTO `gallery` (`title`, `description`, `file_path`, `thumbnail_path`, `media_type`, `album`, `sort_order`, `is_active`) VALUES
('Kegiatan Manasik Haji 2026', 'Suasana meriah kegiatan manasik haji cilik yang diikuti seluruh siswa RA Attakal Yaqiin.', 'uploads/gallery/manasik-haji-2026.jpg', 'uploads/gallery/thumbs/manasik-haji-2026.jpg', 'image', 'Kegiatan Sekolah', 1, 1),
('Wisuda Angkatan 2025', 'Momen bahagia wisuda siswa-siswi RA Attakal Yaqiin angkatan 2025.', 'uploads/gallery/wisuda-2025.jpg', 'uploads/gallery/thumbs/wisuda-2025.jpg', 'image', 'Kegiatan Sekolah', 2, 1),
('Pembelajaran di Kelas', 'Suasana belajar yang menyenangkan di kelas B1 dengan media interaktif.', 'uploads/gallery/belajar-kelas.jpg', 'uploads/gallery/thumbs/belajar-kelas.jpg', 'image', 'Pembelajaran', 3, 1),
('Area Bermain Outdoor', 'Playground outdoor RA Attakal Yaqiin yang aman dan menyenangkan untuk anak-anak.', 'uploads/gallery/playground.jpg', 'uploads/gallery/thumbs/playground.jpg', 'image', 'Fasilitas', 4, 1),
('Perpustakaan Mini', 'Perpustakaan RA Attakal Yaqiin dengan koleksi buku cerita Islami.', 'uploads/gallery/perpustakaan.jpg', 'uploads/gallery/thumbs/perpustakaan.jpg', 'image', 'Fasilitas', 5, 1),
('Sesi Tahfidz Quran', 'Kegiatan menghafal Al-Quran bersama Ustadzah Nurhalimah di musholla.', 'uploads/gallery/tahfidz.jpg', 'uploads/gallery/thumbs/tahfidz.jpg', 'image', 'Pembelajaran', 6, 1),
('Lomba 17 Agustus', 'Keseruan lomba HUT RI yang diikuti seluruh siswa dengan penuh semangat.', 'uploads/gallery/lomba-17agustus.jpg', 'uploads/gallery/thumbs/lomba-17agustus.jpg', 'image', 'Kegiatan Sekolah', 7, 1),
('Pentas Seni Akhir Tahun', 'Penampilan siswa dalam acara pentas seni akhir tahun ajaran.', 'uploads/gallery/pentas-seni.jpg', 'uploads/gallery/thumbs/pentas-seni.jpg', 'image', 'Kegiatan Sekolah', 8, 1);

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- Selesai! Database siap digunakan.
-- ============================================================
