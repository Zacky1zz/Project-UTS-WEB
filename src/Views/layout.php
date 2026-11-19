<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Aplikasi katalog dan peminjaman e-book">
    <title><?= e($title ?: 'Beranda') ?> | E-Book Library</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<a class="skip-link" href="#content">Lompat ke konten</a>

<header class="site-header">
    <div class="container bar">
        <a class="brand" href="/">
            <span class="brand-mark">E</span>
            <span>e-Library</span>
        </a>
        <button class="nav-toggle" id="navToggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="mainNav">☰</button>
        <nav id="mainNav" class="nav" aria-label="Navigasi utama">
            <a href="/ebooks">Katalog</a>
            <?php if ($user): ?>
                <a href="/loans">Peminjaman</a>
                <?php if ($user->getRole() === 'admin'): ?>
                    <a href="/ebooks/create">+ Tambah E-Book</a>
                <?php endif; ?>
                <span class="user-chip"><?= e($user->getName()) ?> · <?= e($user->getRole()) ?></span>
                <form method="post" action="/logout" class="inline">
                    <?= csrf_field() ?>
                    <button class="btn btn-ghost" type="submit">Keluar</button>
                </form>
            <?php else: ?>
                <a href="/login">Masuk</a>
                <a class="btn" href="/register">Daftar</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container" id="content">
    <?php foreach (\App\Core\Session::pullFlash() as $f): ?>
        <div class="alert alert-<?= e($f['type']) ?>" role="alert"><?= e($f['message']) ?></div>
    <?php endforeach; ?>
    <?= $content ?>
</main>

<footer class="site-footer">
    <div class="container">&copy; <?= date('Y') ?> E-Book Library · UTS Pemrograman Web · Informatika UMM</div>
</footer>

<script src="/assets/js/app.js"></script>
</body>
</html>
