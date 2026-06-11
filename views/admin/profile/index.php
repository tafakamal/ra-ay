<?php
/**
 * View Profil Akun
 * RA Attakal Yaqiin
 */

declare(strict_types=1);
?>

<div class="form-row form-row-2">
    <!-- Left Column: Edit Profile Info -->
    <div class="panel-card">
        <div class="panel-header">
            <h2 class="panel-title"><i class="ti ti-user-cog"></i> Profil Akun</h2>
        </div>

        <div class="panel-body">
            <form action="<?= url('/admin/profile/update') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- Avatar Preview & Upload -->
                <div style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 2rem; border-bottom: 1px solid var(--border); padding-bottom: 1.5rem;">
                    <div style="position: relative; margin-bottom: 1rem;">
                        <img id="avatarPreview" src="<?= upload_url($user['avatar'], 'assets/images/default-avatar.png') ?>" 
                             alt="Avatar <?= e($user['name']) ?>" 
                             style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-light); box-shadow: var(--shadow);">
                    </div>
                    <div class="form-group" style="width: 100%; max-width: 300px; margin-bottom: 0;">
                        <label for="avatar" class="form-label" style="font-weight: 500; font-size: 0.85rem;">Ganti Foto Profil</label>
                        <input type="file" name="avatar" id="avatar" class="form-control-file" accept="image/*" onchange="previewAvatar(event)">
                        <span class="form-text">Format: JPG, JPEG, PNG, WEBP. Maks 5MB.</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                    <div style="position: relative;">
                        <i class="ti ti-user" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        <input type="text" name="name" id="name" class="form-control" value="<?= e($user['name']) ?>" placeholder="Nama lengkap Anda" required style="padding-left: 2.5rem;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email <span style="color: var(--danger);">*</span></label>
                    <div style="position: relative;">
                        <i class="ti ti-mail" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        <input type="email" name="email" id="email" class="form-control" value="<?= e($user['email']) ?>" placeholder="nama@email.com" required style="padding-left: 2.5rem;">
                    </div>
                    <span class="form-text">Email ini digunakan sebagai username saat login.</span>
                </div>

                <div class="form-actions" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy"></i> Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Change Password -->
    <div class="panel-card">
        <div class="panel-header">
            <h2 class="panel-title"><i class="ti ti-lock-cog"></i> Ubah Password</h2>
        </div>

        <div class="panel-body">
            <form action="<?= url('/admin/profile/change-password') ?>" method="POST" id="passwordForm">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="old_password" class="form-label">Password Lama <span style="color: var(--danger);">*</span></label>
                    <div style="position: relative;">
                        <i class="ti ti-lock-open" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        <input type="password" name="old_password" id="old_password" class="form-control" placeholder="••••••••" required style="padding-left: 2.5rem;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label">Password Baru <span style="color: var(--danger);">*</span></label>
                    <div style="position: relative;">
                        <i class="ti ti-lock" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="••••••••" required style="padding-left: 2.5rem;">
                    </div>
                    <span class="form-text">Minimal 6 karakter. Gunakan kombinasi huruf dan angka.</span>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Konfirmasi Password Baru <span style="color: var(--danger);">*</span></label>
                    <div style="position: relative;">
                        <i class="ti ti-lock-check" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="••••••••" required style="padding-left: 2.5rem;">
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-secondary">
                        <i class="ti ti-key"></i> Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview avatar image before upload
    function previewAvatar(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('avatarPreview');
            output.src = reader.result;
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    // Client side check for matching passwords
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        passwordForm.onsubmit = function(e) {
            const newPwd = document.getElementById('new_password').value;
            const confPwd = document.getElementById('confirm_password').value;

            if (newPwd.length < 6) {
                alert('Password baru minimal harus 6 karakter.');
                e.preventDefault();
                return false;
            }

            if (newPwd !== confPwd) {
                alert('Konfirmasi password baru tidak cocok.');
                e.preventDefault();
                return false;
            }
            return true;
        }
    }
</script>
