<?php
$palette = ['#bf6b53', '#597b73', '#d09a45', '#566e91', '#7a657f', '#4f7a91'];
$coverColor = $palette[(($ebook->getId() - 1) % count($palette))];
?>
<article class="panel detail-layout">
    <div class="detail-cover<?= $ebook->getCover() ? ' has-image' : '' ?>"<?= $ebook->getCover() ? '' : ' style="--cover-color: ' . $coverColor . '"' ?>>
        <?php if ($ebook->getCover()): ?>
            <img src="<?= e($ebook->getCover()) ?>" alt="Sampul <?= e($ebook->getTitle()) ?>">
        <?php else: ?>
            <span class="cover-category"><?= e($ebook->getCategory()) ?></span>
            <strong class="cover-title"><?= e($ebook->getTitle()) ?></strong>
            <span class="cover-author"><?= e($ebook->getAuthor()) ?> · <?= $ebook->getYear() ?></span>
        <?php endif; ?>
    </div>
    <div class="detail-content">
        <a href="/ebooks" class="detail-back muted">← Kembali ke katalog</a>
        <span class="tag"><?= e($ebook->getCategory()) ?></span>
        <h1><?= e($ebook->getTitle()) ?></h1>
        <p class="muted">Oleh <strong><?= e($ebook->getAuthor()) ?></strong> · Terbit <?= $ebook->getYear() ?></p>
        <p class="detail-description"><?= nl2br(e($ebook->getDescription() ?: 'Belum ada deskripsi.')) ?></p>
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
    </div>
</article>
