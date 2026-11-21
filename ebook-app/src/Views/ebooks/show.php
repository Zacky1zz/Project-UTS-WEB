<article class="panel">
    <a href="/ebooks" class="muted">← Kembali ke katalog</a>
    <span class="tag"><?= e($ebook->getCategory()) ?></span>
    <h1><?= e($ebook->getTitle()) ?></h1>
    <p class="muted">Oleh <strong><?= e($ebook->getAuthor()) ?></strong> · Terbit <?= $ebook->getYear() ?></p>
    <p><?= nl2br(e($ebook->getDescription() ?: 'Belum ada deskripsi.')) ?></p>
    <p class="stock <?= $ebook->isAvailable() ? 'ok' : 'out' ?>">
        <?= $ebook->isAvailable() ? 'Tersedia: ' . $ebook->getStock() : 'Stok habis' ?>
    </p>

    <div class="actions">
        <?php if ($user && $ebook->isAvailable()): ?>
            <form method="post" action="/ebooks/<?= $ebook->getId() ?>/borrow" class="inline">
                <?= csrf_field() ?>
                <button class="btn" type="submit">Pinjam</button>
            </form>
        <?php elseif (!$user): ?>
            <a class="btn" href="/login">Masuk untuk meminjam</a>
        <?php endif; ?>

        <?php if ($user && $user->getRole() === 'admin'): ?>
            <a class="btn btn-ghost" href="/ebooks/<?= $ebook->getId() ?>/edit">Edit</a>
            <form method="post" action="/ebooks/<?= $ebook->getId() ?>/delete" class="inline" data-confirm="Hapus e-book ini?">
                <?= csrf_field() ?>
                <button class="btn btn-danger" type="submit">Hapus</button>
            </form>
        <?php endif; ?>
    </div>
</article>
