<section class="panel narrow">
    <h1>Daftar Member</h1>
    <?php if ($error): ?><div class="alert alert-error" role="alert"><?= e($error) ?></div><?php endif; ?>
    <form method="post" action="/register" data-validate novalidate>
        <?= csrf_field() ?>
        <div class="field">
            <label for="name">Nama lengkap</label>
            <input type="text" id="name" name="name" value="<?= old($old, 'name') ?>" required minlength="3" autocomplete="name">
            <small class="error-text"></small>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= old($old, 'email') ?>" required autocomplete="email">
            <small class="error-text"></small>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required minlength="6" autocomplete="new-password">
            <div class="meter" aria-hidden="true"><span id="meterBar"></span></div>
            <small class="error-text"></small>
        </div>
        <div class="field">
            <label for="confirm">Konfirmasi password</label>
            <input type="password" id="confirm" name="confirm" required data-match="password" autocomplete="new-password">
            <small class="error-text"></small>
        </div>
        <button class="btn btn-block" type="submit">Buat Akun</button>
    </form>
    <p class="muted center">Sudah punya akun? <a href="/login">Masuk</a></p>
</section>
