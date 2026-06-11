<?php
/**
 * View Detail Pesan Kontak
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<div class="panel-card" style="max-width: 800px; margin: 0 auto;">
    <div class="panel-header">
        <h2 class="panel-title"><i class="ti ti-mail-opened"></i> Detail Pesan Kontak</h2>
        <a href="<?= url('/admin/contacts') ?>" class="btn btn-outline">
            <i class="ti ti-arrow-left"></i> Kembali ke Kotak Masuk
        </a>
    </div>

    <div class="panel-body" style="padding: 2rem;">
        <!-- Message Metadata Headers -->
        <div style="background-color: var(--light); padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 2rem;">
            <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem;">
                <!-- Sender -->
                <div style="display: flex; gap: 0.5rem; align-items: baseline;">
                    <span style="font-weight: 600; width: 120px; color: var(--secondary); display: inline-block;">Nama Pengirim:</span>
                    <span style="font-size: 1.05rem; font-weight: 700; color: var(--dark);"><?= e($message['name']) ?></span>
                </div>
                
                <!-- Email -->
                <div style="display: flex; gap: 0.5rem; align-items: baseline;">
                    <span style="font-weight: 600; width: 120px; color: var(--secondary); display: inline-block;">Email:</span>
                    <a href="mailto:<?= e($message['email']) ?>" style="color: var(--primary); font-weight: 600; text-decoration: underline;">
                        <?= e($message['email']) ?>
                    </a>
                </div>

                <!-- Phone -->
                <div style="display: flex; gap: 0.5rem; align-items: baseline;">
                    <span style="font-weight: 600; width: 120px; color: var(--secondary); display: inline-block;">No. Telepon/HP:</span>
                    <span>
                        <?php if (!empty($message['phone'])): ?>
                            <a href="tel:<?= e($message['phone']) ?>" style="color: var(--secondary); font-weight: 500; text-decoration: underline;"><?= e($message['phone']) ?></a>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $message['phone']) ?>" target="_blank" class="btn btn-outline btn-sm" style="margin-left: 0.5rem; padding: 0.15rem 0.4rem; color: #25D366; border-color: #25D366; font-size: 0.75rem;">
                                <i class="ti ti-brand-whatsapp"></i> Hubungi via WA
                            </a>
                        <?php else: ?>
                            <span style="color: var(--text-muted); font-style: italic;">Tidak ada nomor telepon</span>
                        <?php endif; ?>
                    </span>
                </div>

                <!-- Date -->
                <div style="display: flex; gap: 0.5rem; align-items: baseline;">
                    <span style="font-weight: 600; width: 120px; color: var(--secondary); display: inline-block;">Tanggal Kirim:</span>
                    <span style="color: var(--text-main); font-size: 0.95rem;">
                        <?= format_date($message['created_at'], 'datetime') ?>
                    </span>
                </div>

                <!-- IP Address -->
                <div style="display: flex; gap: 0.5rem; align-items: baseline;">
                    <span style="font-weight: 600; width: 120px; color: var(--secondary); display: inline-block;">Alamat IP:</span>
                    <span style="font-family: monospace; color: var(--text-muted); font-size: 0.85rem;">
                        <?= e($message['ip_address']) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Subject -->
        <div style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
            <span style="font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Subjek / Perihal:</span>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--secondary);"><?= e($message['subject'] !== '' ? $message['subject'] : '(Tanpa Subjek)') ?></h3>
        </div>

        <!-- Message Body -->
        <div style="margin-bottom: 3rem;">
            <span style="font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">Isi Pesan:</span>
            <div style="background-color: #ffffff; border: 1px solid var(--border); padding: 1.5rem; border-radius: 8px; line-height: 1.7; font-size: 1rem; white-space: pre-wrap; color: var(--text-main); min-height: 150px;">
                <?= e($message['message']) ?>
            </div>
        </div>

        <!-- Actions -->
        <div style="display: flex; gap: 1rem; border-top: 1px solid var(--border); padding-top: 1.5rem;">
            <a href="mailto:<?= e($message['email']) ?>?subject=Re: <?= rawurlencode($message['subject']) ?>" class="btn btn-primary">
                <i class="ti ti-mail-forward"></i> Balas Lewat Email
            </a>
            
            <a href="<?= url('/admin/contacts/delete/' . $message['id']) ?>" 
               class="btn btn-danger" 
               onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')" 
               style="margin-left: auto;">
                <i class="ti ti-trash"></i> Hapus Pesan
            </a>
        </div>
    </div>
</div>
