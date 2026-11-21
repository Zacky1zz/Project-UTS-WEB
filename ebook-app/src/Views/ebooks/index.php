<section class="hero">
    <h1>Katalog E-Book</h1>
    <p>Cari dan pinjam e-book favoritmu.</p>
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

<p class="muted"><span id="count"><?= count($ebooks) ?></span> e-book ditemukan</p>

<section class="grid" id="grid" aria-label="Daftar e-book">
    <?php foreach ($ebooks as $b): ?>
        <article class="card" data-search="<?= e(mb_strtolower($b->getTitle() . ' ' . $b->getAuthor())) ?>" data-category="<?= e($b->getCategory()) ?>">
            <span class="tag"><?= e($b->getCategory()) ?></span>
            <h2><a href="/ebooks/<?= $b->getId() ?>"><?= e($b->getTitle()) ?></a></h2>
            <p class="muted"><?= e($b->getAuthor()) ?> · <?= $b->getYear() ?></p>
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
        </article>
    <?php endforeach; ?>
</section>
<p id="empty" class="muted center" <?= $ebooks ? 'hidden' : '' ?>>Tidak ada e-book yang cocok.</p>
