<section class="panel narrow">
    <h1>Masuk</h1>
    <?php if ($error): ?><div class="alert alert-error" role="alert"><?= e($error) ?></div><?php endif; ?>
    <form method="post" action="/login" data-validate novalidate>
        <?= csrf_field() ?>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= old($old, 'email') ?>" required autocomplete="email">
            <small class="error-text"></small>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
            <small class="error-text"></small>
        </div>
        <button class="btn btn-block" type="submit">Masuk</button>
    </form>
    <p class="muted center">Belum punya akun? <a href="/register">Daftar di sini</a></p>
    <p class="hint">Demo: <code>admin@ebook.test / admin123</code> · <code>member@ebook.test / member123</code></p>
</section>
