<?php
require_once __DIR__ . '/db.php';
$postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$postId || $postId < 1) {
    header('Location: index.php');
    exit;
}
$updateView = $conn->prepare('UPDATE posts SET view_count = view_count + 1 WHERE ID = ?');
$updateView->bind_param('i', $postId);
$updateView->execute();
$statement = $conn->prepare('SELECT ID, title, summary, content, category, created_at, view_count FROM posts WHERE ID = ? LIMIT 1');
$statement->bind_param('i', $postId);
$statement->execute();
$post = $statement->get_result()->fetch_assoc();
if (!$post) { http_response_code(404); exit('Yazı bulunamadı.'); }
$categoryNames = [1 => 'Antrenman', 2 => 'Bilgi', 3 => 'Paketler'];
$relatedStatement = $conn->prepare('SELECT ID, title FROM posts WHERE ID <> ? ORDER BY ID DESC LIMIT 5');
$relatedStatement->bind_param('i', $postId);
$relatedStatement->execute();
$relatedPosts = $relatedStatement->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html lang="tr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= e($post['title']) ?> | Kadir Coaching</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="assets/css/style.css"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark"><div class="container"><a class="navbar-brand fw-bold" href="index.php"><span class="navbar-brand-mark">KC</span>Kadir Coaching</a><a class="nav-link text-white" href="index.php">Anasayfa</a></div></nav>
<main class="container py-5"><div class="row g-4"><article class="col-lg-8"><a href="index.php" class="text-decoration-none text-secondary">← Anasayfaya dön</a><div class="d-flex gap-2 align-items-center mt-4 mb-2"><span class="badge text-bg-danger"><?= e($categoryNames[(int)$post['category']] ?? 'Genel') ?></span><small class="text-secondary"><?= e(date('d.m.Y H:i', strtotime($post['created_at']))) ?> · <?= (int)$post['view_count'] ?> görüntülenme</small></div><h1 class="fw-bold mb-4"><?= e($post['title']) ?></h1><?php if ($post['summary'] !== ''): ?><p class="lead text-secondary"><?= e($post['summary']) ?></p><?php endif; ?><div class="post-content"><?= $post['content'] ?></div><?php if ((int)$post['category'] === 3 && $whatsappNumber !== ''): ?><div class="alert alert-light border mt-4"><strong>Bu içerik hakkında bilgi alın.</strong><div><a class="btn btn-dark mt-2" href="https://wa.me/<?= e($whatsappNumber) ?>" target="_blank" rel="noopener noreferrer">WhatsApp</a></div></div><?php endif; ?></article><aside class="col-lg-4"><div class="card admin-card"><div class="card-body"><h2 class="h5 fw-bold">Diğer yazılar</h2><ul class="list-unstyled mb-0"><?php foreach ($relatedPosts as $related): ?><li class="py-2 border-bottom"><a class="text-decoration-none" href="post.php?id=<?= (int)$related['ID'] ?>"><?= e($related['title']) ?></a></li><?php endforeach; ?></ul></div></div></aside></div></main>
<footer class="site-footer py-4"><div class="container text-center small">Kadir Coaching · Örnek PHP/MySQL projesi</div></footer><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html>
