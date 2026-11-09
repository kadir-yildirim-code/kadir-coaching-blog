<?php
require_once __DIR__ . '/auth.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!csrf_is_valid()) {
        $message = 'Oturum doğrulaması başarısız. İşlem yapılmadı.';
    } else {
        $deleteId = filter_input(INPUT_POST, 'delete_id', FILTER_VALIDATE_INT);
        if ($deleteId && $deleteId > 0) {
            $delete = $conn->prepare('DELETE FROM posts WHERE ID = ?');
            $delete->bind_param('i', $deleteId);
            $delete->execute();
            $message = $delete->affected_rows ? 'Yazı silindi.' : 'Silinecek yazı bulunamadı.';
        }
    }
}

$pageTitle = 'Yazıları yönet';
$categories = [1 => 'Antrenman', 2 => 'Bilgi', 3 => 'Paketler'];

// Read and validate filters from the query string.
$search = trim((string)($_GET['q'] ?? ''));
$search = mb_substr($search, 0, 100, 'UTF-8');

$categoryFilter = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);
if ($categoryFilter === false || $categoryFilter === null || !array_key_exists($categoryFilter, $categories)) {
    $categoryFilter = 0;
}

$currentPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$currentPage = ($currentPage === false || $currentPage === null) ? 1 : max(1, $currentPage);

$perPage = 10;
$searchPattern = '%' . $search . '%';

// Count matching records so the pagination controls reflect the current filters.
$countStatement = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM posts
     WHERE (? = '' OR title LIKE ?)
       AND (? = 0 OR category = ?)"
);
$countStatement->bind_param('ssii', $search, $searchPattern, $categoryFilter, $categoryFilter);
$countStatement->execute();
$totalPosts = (int)$countStatement->get_result()->fetch_assoc()['total'];

$totalPages = max(1, (int)ceil($totalPosts / $perPage));
$currentPage = min($currentPage, $totalPages);
$offset = ($currentPage - 1) * $perPage;

// Fetch only the requested page. All user-provided values remain bound parameters.
$listStatement = $conn->prepare(
    "SELECT ID, title, category, created_at, view_count
     FROM posts
     WHERE (? = '' OR title LIKE ?)
       AND (? = 0 OR category = ?)
     ORDER BY created_at DESC, ID DESC
     LIMIT ? OFFSET ?"
);
$listStatement->bind_param(
    'ssiiii',
    $search,
    $searchPattern,
    $categoryFilter,
    $categoryFilter,
    $perPage,
    $offset
);
$listStatement->execute();
$posts = $listStatement->get_result()->fetch_all(MYSQLI_ASSOC);

$firstResult = $totalPosts === 0 ? 0 : $offset + 1;
$lastResult = min($offset + $perPage, $totalPosts);

$queryForPage = static function (int $targetPage) use ($search, $categoryFilter): string {
    $parameters = [
        'q' => $search,
        'category' => $categoryFilter,
        'page' => $targetPage,
    ];
    $parameters = array_filter($parameters, static fn($value) => $value !== '' && $value !== 0);
    return 'yazilar.php?' . http_build_query($parameters);
};

require __DIR__ . '/partials/header.php';
?>

<?php if ($message !== ''): ?>
    <div class="alert alert-info"><?= e($message) ?></div>
<?php endif; ?>

<div class="card admin-card">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h2 class="h5 fw-bold mb-1">Yayınlanan yazılar</h2>
                <p class="text-secondary small mb-0">
                    <?= $totalPosts ?> sonuç · <?= $firstResult ?>–<?= $lastResult ?> arası gösteriliyor
                </p>
            </div>
            <a class="btn btn-danger btn-sm" href="yazi_ekle.php">Yeni yazı</a>
        </div>

        <form method="get" class="row g-2 align-items-end mb-4">
            <div class="col-md-6">
                <label class="form-label" for="q">Başlığa göre ara</label>
                <input
                    class="form-control"
                    type="search"
                    id="q"
                    name="q"
                    value="<?= e($search) ?>"
                    maxlength="100"
                    placeholder="Yazı başlığı..."
                >
            </div>
            <div class="col-md-3">
                <label class="form-label" for="category">Kategori</label>
                <select class="form-select" id="category" name="category">
                    <option value="0">Tüm kategoriler</option>
                    <?php foreach ($categories as $value => $label): ?>
                        <option value="<?= $value ?>" <?= $categoryFilter === $value ? 'selected' : '' ?>>
                            <?= e($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-outline-primary flex-grow-1" type="submit">Filtrele</button>
                <a class="btn btn-outline-secondary" href="yazilar.php">Temizle</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Başlık</th>
                        <th>Kategori</th>
                        <th>Görüntülenme</th>
                        <th>Tarih</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($posts): ?>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?= (int)$post['ID'] ?></td>
                                <td><?= e($post['title']) ?></td>
                                <td><?= e($categories[(int)$post['category']] ?? 'Genel') ?></td>
                                <td><?= (int)$post['view_count'] ?></td>
                                <td><?= e(date('d.m.Y', strtotime($post['created_at']))) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-outline-primary btn-sm" href="yazi_duzenle.php?id=<?= (int)$post['ID'] ?>">Düzenle</a>
                                        <form method="post" onsubmit="return confirm('Bu yazıyı silmek istediğine emin misin?');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="delete_id" value="<?= (int)$post['ID'] ?>">
                                            <button class="btn btn-outline-danger btn-sm" type="submit">Sil</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-4">
                                Aramanla eşleşen yazı bulunamadı.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <?php $startPage = max(1, $currentPage - 2); ?>
            <?php $endPage = min($totalPages, $currentPage + 2); ?>
            <nav aria-label="Yazı sayfaları">
                <ul class="pagination justify-content-center flex-wrap mb-0">
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= e($queryForPage(max(1, $currentPage - 1))) ?>" aria-label="Önceki">Önceki</a>
                    </li>
                    <?php if ($startPage > 1): ?>
                        <li class="page-item"><a class="page-link" href="<?= e($queryForPage(1)) ?>">1</a></li>
                        <?php if ($startPage > 2): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                    <?php endif; ?>
                    <?php for ($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++): ?>
                        <li class="page-item <?= $pageNumber === $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="<?= e($queryForPage($pageNumber)) ?>" <?= $pageNumber === $currentPage ? 'aria-current="page"' : '' ?>>
                                <?= $pageNumber ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($endPage < $totalPages): ?>
                        <?php if ($endPage < $totalPages - 1): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                        <li class="page-item"><a class="page-link" href="<?= e($queryForPage($totalPages)) ?>"><?= $totalPages ?></a></li>
                    <?php endif; ?>
                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= e($queryForPage(min($totalPages, $currentPage + 1))) ?>" aria-label="Sonraki">Sonraki</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
