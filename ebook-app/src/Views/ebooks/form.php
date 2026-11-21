<section class="panel narrow">
    <h1><?= e($heading) ?></h1>
    <form method="post" action="<?= e($action) ?>" data-validate novalidate>
        <?= csrf_field() ?>
        <?php
        $fields = [
            ['title', 'Judul', 'text'], ['author', 'Penulis', 'text'],
            ['category', 'Kategori', 'text'], ['year', 'Tahun terbit', 'number'], ['stock', 'Stok', 'number'],
        ];
        foreach ($fields as [$name, $label, $type]): ?>
            <div class="field">
                <label for="<?= $name ?>"><?= $label ?></label>
                <input type="<?= $type ?>" id="<?= $name ?>" name="<?= $name ?>" value="<?= old($old, $name) ?>" required
                    <?= $name === 'year' ? 'min="1000" max="' . ((int) date('Y') + 1) . '"' : '' ?>
                    <?= $name === 'stock' ? 'min="0"' : '' ?>>
                <small class="error-text"><?= e($errors[$name] ?? '') ?></small>
            </div>
        <?php endforeach; ?>
        <div class="field">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="4"><?= old($old, 'description') ?></textarea>
        </div>
        <div class="actions">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn btn-ghost" href="/ebooks">Batal</a>
        </div>
    </form>
</section>
