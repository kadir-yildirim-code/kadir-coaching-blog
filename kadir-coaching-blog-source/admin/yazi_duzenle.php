<?php
require_once __DIR__ . '/auth.php';
$postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$postId || $postId < 1) { http_response_code(400); exit('Geçersiz yazı numarası.'); }
$fetch = $conn->prepare('SELECT ID, title, summary, content, category FROM posts WHERE ID = ? LIMIT 1');
$fetch->bind_param('i', $postId); $fetch->execute();
$post = $fetch->get_result()->fetch_assoc();
if (!$post) { http_response_code(404); exit('Yazı bulunamadı.'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim((string)($_POST['title'] ?? ''));
    $summary = trim((string)($_POST['summary'] ?? ''));
    $content = trim((string)($_POST['content'] ?? ''));
    $category = (int)($_POST['category'] ?? 1);
    if (!csrf_is_valid()) { $error = 'Oturum doğrulaması başarısız.'; }
    elseif ($title === '' || $content === '' || !in_array($category, [1, 2, 3], true)) { $error = 'Başlık, içerik ve geçerli bir kategori girin.'; }
    else {
        $update = $conn->prepare('UPDATE posts SET title = ?, summary = ?, content = ?, category = ? WHERE ID = ?');
        $update->bind_param('sssii', $title, $summary, $content, $category, $postId);
        $update->execute();
        header('Location: yazilar.php'); exit;
    }
    $post['title'] = $title; $post['summary'] = $summary; $post['content'] = $content; $post['category'] = $category;
}
$pageTitle = 'Yazıyı düzenle';
require __DIR__ . '/partials/header.php';
?>
<?php if ($error !== ''): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<div class="card admin-card"><div class="card-body"><form method="post"><?= csrf_field() ?><div class="mb-3"><label class="form-label" for="title">Başlık</label><input class="form-control" id="title" name="title" value="<?= e($post['title']) ?>" required maxlength="255"></div><div class="mb-3"><label class="form-label" for="category">Kategori</label><select class="form-select" id="category" name="category"><?php foreach ([1 => 'Antrenman', 2 => 'Bilgi', 3 => 'Paketler'] as $value => $label): ?><option value="<?= $value ?>" <?= (int)$post['category'] === $value ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select></div><div class="mb-3"><label class="form-label" for="summary">Kısa özet</label><textarea class="form-control" id="summary" name="summary" rows="3"><?= e($post['summary']) ?></textarea></div><div class="mb-3"><label class="form-label" for="content">İçerik (HTML destekler)</label><textarea class="form-control font-monospace" id="content" name="content" rows="12" required><?= e($post['content']) ?></textarea><div class="form-text">İçerik alanı HTML kabul eder. Yalnızca güvendiğin içerikleri yayınla.</div></div><button class="btn btn-danger" type="submit">Değişiklikleri kaydet</button> <a class="btn btn-outline-secondary" href="yazilar.php">Vazgeç</a></form></div></div>
<?php require __DIR__ . '/partials/footer.php'; ?>
