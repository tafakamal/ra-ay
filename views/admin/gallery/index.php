<?php
/**
 * View Index Galeri Foto & Video
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<div class="panel-card">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-photo-album"></i> Galeri Foto & Video</h2>
        <a href="<?= url('/admin/gallery/create') ?>" class="btn btn-primary">
            <i class="ti ti-plus"></i> Tambah Item Galeri
        </a>
    </div>

    <div class="panel-body">
        <!-- Album Filter Form -->
        <form action="<?= url('/admin/gallery') ?>" method="GET" style="margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="width: 250px; margin-bottom: 0;">
                <label for="album" class="form-label">Filter Album</label>
                <select name="album" id="album" class="form-select">
                    <option value="">Semua Album</option>
                    <?php foreach ($albums as $a): ?>
                        <?php if (!empty($a['album'])): ?>
                            <option value="<?= e($a['album']) ?>" <?= $albumFilter === $a['album'] ? 'selected' : '' ?>>
                                <?= e($a['album']) ?> (<?= $a['item_count'] ?> item)
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-secondary">
                    <i class="ti ti-filter"></i> Filter
                </button>
                <?php if ($albumFilter !== ''): ?>
                    <a href="<?= url('/admin/gallery') ?>" class="btn btn-outline">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <!-- Gallery Grid -->
        <?php if (empty($galleryItems)): ?>
            <div style="text-align: center; padding: 4rem 1rem; color: var(--text-muted);">
                <i class="ti ti-photo-off" style="font-size: 3.5rem; display: block; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p>Belum ada foto atau video dalam album ini.</p>
            </div>
        <?php else: ?>
            <div class="gallery-grid">
                <?php foreach ($galleryItems as $item): ?>
                    <div class="gallery-card">
                        <div class="gallery-img-wrapper">
                            <?php if ($item['type'] === 'video'): ?>
                                <!-- Video Thumbnail fallback or a black board with play icon -->
                                <?php if (!empty($item['thumbnail_path'])): ?>
                                    <img src="<?= upload_url($item['thumbnail_path']) ?>" alt="Video Thumbnail" class="gallery-img">
                                <?php else: ?>
                                    <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: #000; display:flex; align-items:center; justify-content:center;">
                                        <i class="ti ti-video" style="color: #fff; font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="gallery-type-badge"><i class="ti ti-brand-youtube"></i> Video</span>
                                <!-- Play Icon Overlay -->
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 44px; height: 44px; border-radius: 50%; background-color: rgba(232, 96, 10, 0.9); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; pointer-events: none;">
                                    <i class="ti ti-play"></i>
                                </div>
                            <?php else: ?>
                                <img src="<?= upload_url($item['thumbnail_path']) ?>" alt="<?= e($item['title']) ?>" class="gallery-img">
                                <span class="gallery-type-badge"><i class="ti ti-photo"></i> Foto</span>
                            <?php endif; ?>
                        </div>

                        <div class="gallery-info">
                            <span class="gallery-album"><i class="ti ti-folder"></i> <?= e($item['album'] ?? 'Umum') ?></span>
                            <h3 class="gallery-title" title="<?= e($item['title']) ?>"><?= e(truncate($item['title'], 40)) ?></h3>
                            <p class="gallery-desc"><?= e(truncate($item['description'] ?? '', 80)) ?></p>
                            
                            <div style="margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; color: var(--text-muted);">
                                <span>Urutan: <strong><?= $item['sort_order'] ?></strong></span>
                                <span class="badge badge-<?= $item['is_active'] ? 'success' : 'danger' ?>" style="padding: 0.15rem 0.4rem; font-size: 0.7rem;">
                                    <?= $item['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </div>

                            <div class="gallery-actions">
                                <a href="<?= url('/admin/gallery/edit/' . $item['id']) ?>" class="btn btn-outline btn-sm" title="Edit Item">
                                    <i class="ti ti-edit" style="color: var(--primary);"></i> Edit
                                </a>
                                <a href="<?= url('/admin/gallery/delete/' . $item['id']) ?>" 
                                   class="btn btn-outline btn-sm" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus item galeri ini?')" 
                                   title="Hapus Item"
                                   style="border-color: rgba(239, 68, 68, 0.2);">
                                    <i class="ti ti-trash" style="color: var(--danger);"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
