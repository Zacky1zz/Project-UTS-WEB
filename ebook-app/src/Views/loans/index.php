<section class="hero">
    <h1><?= $isAdmin ? 'Semua Peminjaman' : 'Peminjaman Saya' ?></h1>
</section>

<?php if (!$rows): ?>
    <p class="muted">Belum ada transaksi peminjaman. <a href="/ebooks">Lihat katalog</a>.</p>
<?php else: ?>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>E-Book</th>
                <?php if ($isAdmin): ?><th>Peminjam</th><?php endif; ?>
                <th>Dipinjam</th><th>Jatuh Tempo</th><th>Status</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as ['loan' => $loan, 'ebook' => $ebook, 'owner' => $owner]): ?>
            <tr>
                <td><?= e($ebook?->getTitle() ?? '(e-book dihapus)') ?></td>
                <?php if ($isAdmin): ?><td><?= e($owner?->getName() ?? '-') ?></td><?php endif; ?>
                <td><?= format_date($loan->getBorrowedAt()) ?></td>
                <td><?= format_date($loan->getDueDate()) ?></td>
                <td>
                    <?php if ($loan->isReturned()): ?>
                        <span class="badge ok">Dikembalikan <?= format_date($loan->getReturnedAt()) ?></span>
                    <?php elseif ($loan->isOverdue()): ?>
                        <span class="badge out">Terlambat</span>
                    <?php else: ?>
                        <span class="badge warn">Dipinjam</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!$loan->isReturned()): ?>
                        <form method="post" action="/loans/<?= $loan->getId() ?>/return" class="inline" data-confirm="Kembalikan e-book ini?">
                            <?= csrf_field() ?>
                            <button class="btn btn-ghost" type="submit">Kembalikan</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
