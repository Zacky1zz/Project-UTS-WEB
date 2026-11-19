<section class="catalog-hero">
    <div class="hero-copy">
        <p class="eyebrow">Perpustakaan digital</p>
        <h1>Temukan cerita berikutnya.</h1>
        <p>Jelajahi koleksi, temukan favoritmu, dan pinjam dalam sekali sentuh.</p>
    </div>
</section>

<form method="get" action="/ebooks" class="filters" role="search">
    <label class="sr-only" for="search">Cari judul atau penulis</label>
    <input type="search" id="search" name="q" value="<?= e($q) ?>" placeholder="Cari judul atau penulis…">
    <label class="sr-only" for="category">Kategori</label>
    <select id="category" name="category">
        <option value="">Semua kategori</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?= e($c) ?>" <?= $c === $category ? 'selected' : '' ?>><?= e($c) ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn" type="submit">Cari</button>
</form>

<p class="results-line"><span><strong id="count"><?= count($ebooks) ?></strong> koleksi ditemukan</span><span>Disusun untukmu</span></p>

<section class="grid" id="grid" aria-label="Daftar e-book">
    <?php foreach ($ebooks as $b): ?>
        <?php
        $palette = ['#bf6b53', '#597b73', '#d09a45', '#566e91', '#7a657f', '#4f7a91'];
        $coverColor = $palette[(($b->getId() - 1) % count($palette))];
        ?>
        <article class="card" data-search="<?= e(mb_strtolower($b->getTitle() . ' ' . $b->getAuthor())) ?>" data-category="<?= e($b->getCategory()) ?>">
            <a class="book-cover<?= $b->getCover() ? ' has-image' : '' ?>" href="/ebooks/<?= $b->getId() ?>"<?= $b->getCover() ? '' : ' style="--cover-color: ' . $coverColor . '"' ?>>
                <?php if ($b->getCover()): ?>
                    <img src="<?= e($b->getCover()) ?>" alt="Sampul <?= e($b->getTitle()) ?>">
                <?php else: ?>
                    <span class="cover-category"><?= e($b->getCategory()) ?></span>
                    <strong class="cover-title"><?= e($b->getTitle()) ?></strong>
                    <span class="cover-author"><?= e($b->getAuthor()) ?></span>
                <?php endif; ?>
            </a>
            <div class="card-info">
                <span class="tag"><?= e($b->getCategory()) ?></span>
                <h2><a href="/ebooks/<?= $b->getId() ?>"><?= e($b->getTitle()) ?></a></h2>
                <p class="card-meta"><?= e($b->getAuthor()) ?> · <?= $b->getYear() ?></p>
                <div class="card-spacer"></div>
            <p class="stock <?= $b->isAvailable() ? 'ok' : 'out' ?>">
                <?= $b->isAvailable() ? 'Tersedia: ' . $b->getStock() : 'Stok habis' ?>
            </p>
            <div class="actions">
                <a class="btn btn-ghost" href="/ebooks/<?= $b->getId() ?>">Detail</a>
                <?php if ($user && $b->isAvailable()): ?>
                    <form method="post" action="/ebooks/<?= $b->getId() ?>/borrow" class="inline">
                        <?= csrf_field() ?>
                        <button class="btn" type="submit">Pinjam</button>
                    </form>
                <?php endif; ?>
            </div>
            </div>
        </article>
    <?php endforeach; ?>
</section>
<p id="empty" class="muted center" <?= $ebooks ? 'hidden' : '' ?>>Tidak ada e-book yang cocok.</p>
