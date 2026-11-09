<?php
require_once __DIR__ . '/db.php';

$category = filter_input(INPUT_GET, 'cat', FILTER_VALIDATE_INT);
if ($category !== null && $category !== false && in_array($category, [1, 2, 3], true)) {
    $statement = $conn->prepare('SELECT ID, title, summary, category, created_at, view_count FROM posts WHERE category = ? ORDER BY view_count DESC, created_at DESC');
    $statement->bind_param('i', $category);
} else {
    $statement = $conn->prepare('SELECT ID, title, summary, category, created_at, view_count FROM posts ORDER BY view_count DESC, created_at DESC LIMIT 6');
}
$statement->execute();
$posts = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
$categories = [1 => 'Antrenman', 2 => 'Bilgi', 3 => 'Paketler'];
?>
<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kadir Coaching | Antrenman ve Bilgi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm"><div class="container">
  <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php"><span class="navbar-brand-mark">KC</span>Kadir Coaching</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Menüyü aç"><span class="navbar-toggler-icon"></span></button>
  <div class="collapse navbar-collapse" id="mainNav"><ul class="navbar-nav ms-auto">
    <li class="nav-item"><a class="nav-link" href="index.php">Anasayfa</a></li>
    <li class="nav-item"><a class="nav-link" href="index.php?cat=1">Antrenman</a></li>
    <li class="nav-item"><a class="nav-link" href="index.php?cat=2">Bilgi</a></li>
    <li class="nav-item"><a class="nav-link" href="index.php?cat=3">Paketler</a></li>
    <li class="nav-item"><a class="nav-link" href="hakkimda.php">Hakkımda</a></li>
  </ul></div>
</div></nav>
<header class="hero-section"><div class="container"><h1 class="display-4 fw-bold">Değişim Bugün Başlıyor</h1><p class="lead mb-0">Hedefine odaklan, gelişimini planla.</p></div></header>
<main class="container py-4 py-lg-5"><div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2"><div><h2 class="h3 fw-bold mb-1">Son Yazılar</h2><p class="text-secondary mb-0">Antrenman, beslenme ve fitness hakkında içerikler.</p></div><a class="btn btn-outline-dark rounded-pill" href="admin/admingiris.php">Yönetici Girişi</a></div>
<div class="row g-4">
<?php if ($posts): foreach ($posts as $post): ?>
  <div class="col-md-6 col-lg-4"><article class="card post-card h-100 shadow-sm"><div class="card-body d-flex flex-column p-4">
    <div class="d-flex justify-content-between align-items-center mb-3"><span class="badge text-bg-danger category-badge"><?= e($categories[(int)$post['category']] ?? 'Genel') ?></span><small class="text-secondary"><?= e(date('d.m.Y', strtotime($post['created_at']))) ?></small></div>
    <h3 class="h5 fw-bold"><a class="text-decoration-none text-dark" href="post.php?id=<?= (int)$post['ID'] ?>"><?= e($post['title']) ?></a></h3>
    <p class="text-secondary flex-grow-1"><?= e(mb_strimwidth($post['summary'], 0, 135, '…', 'UTF-8')) ?></p>
    <div class="d-flex justify-content-between align-items-center border-top pt-3"><small class="text-secondary"><?= (int)$post['view_count'] ?> görüntülenme</small><a href="post.php?id=<?= (int)$post['ID'] ?>" class="btn btn-sm btn-outline-danger rounded-pill">İncele</a></div>
  </div></article></div>
<?php endforeach; else: ?>
  <div class="col-12"><div class="alert alert-light border">Bu kategoride henüz yazı bulunmuyor.</div></div>
<?php endif; ?>
</div></main>
<footer class="site-footer py-4 mt-5"><div class="container text-center small">Kadir Coaching · PHP ve MySQL ile geliştirilmiş örnek içerik yönetim projesi</div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
