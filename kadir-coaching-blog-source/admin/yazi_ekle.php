<?php
require_once __DIR__ . '/auth.php';
$error = '';
$title = $summary = $content = '';
$category = 1;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim((string)($_POST['title'] ?? ''));
    $summary = trim((string)($_POST['summary'] ?? ''));
    $content = trim((string)($_POST['content'] ?? ''));
    $category = (int)($_POST['category'] ?? 1);
    if (!csrf_is_valid()) { $error = 'Oturum doğrulaması başarısız.'; }
    elseif ($title === '' || $content === '' || !in_array($category, [1, 2, 3], true)) { $error = 'Başlık, içerik ve geçerli bir kategori girin.'; }
    else {
        $insert = $conn->prepare('INSERT INTO posts (title, summary, content, category, view_count) VALUES (?, ?, ?, ?, 0)');
        $insert->bind_param('sssi', $title, $summary, $content, $category);
        $insert->execute();
        header('Location: yazilar.php'); exit;
    }
}
$pageTitle = 'Yeni yazı ekle';
require __DIR__ . '/partials/header.php';
?>
<?php if ($error !== ''): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<div class="card admin-card"><div class="card-body"><form method="post"><?= csrf_field() ?><div class="mb-3"><label class="form-label" for="title">Başlık</label><input class="form-control" id="title" name="title" value="<?= e($title) ?>" required maxlength="255"></div><div class="mb-3"><label class="form-label" for="category">Kategori</label><select class="form-select" id="category" name="category"><option value="1" <?= $category === 1 ? 'selected' : '' ?>>Antrenman</option><option value="2" <?= $category === 2 ? 'selected' : '' ?>>Bilgi</option><option value="3" <?= $category === 3 ? 'selected' : '' ?>>Paketler</option></select></div><div class="mb-3"><label class="form-label" for="summary">Kısa özet</label><textarea class="form-control" id="summary" name="summary" rows="3" required><?= e($summary) ?></textarea></div><div class="mb-3"><label class="form-label" for="content">İçerik (HTML destekler)</label><textarea class="form-control font-monospace" id="content" name="content" rows="12" required><?= e($content) ?></textarea><div class="form-text">İçerik alanı HTML kabul eder. Yalnızca güvendiğin, kendi hazırladığın içerikleri yayınla.</div></div><button class="btn btn-danger" type="submit">Yazıyı yayınla</button> <a class="btn btn-outline-secondary" href="yazilar.php">Vazgeç</a></form></div></div>
<?php require __DIR__ . '/partials/footer.php'; ?>
