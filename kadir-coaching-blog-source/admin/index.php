<?php
require_once __DIR__ . '/auth.php';
$pageTitle = 'Dashboard';
$counts = [1 => 0, 2 => 0, 3 => 0];
$result = $conn->query('SELECT category, COUNT(*) AS total FROM posts GROUP BY category');
while ($row = $result->fetch_assoc()) { $counts[(int)$row['category']] = (int)$row['total']; }
$total = array_sum($counts);
require __DIR__ . '/partials/header.php';
?>
<div class="row g-3 mb-4"><?php foreach ([1 => 'Antrenman yazıları', 2 => 'Bilgi yazıları', 3 => 'Paket yazıları'] as $key => $label): ?><div class="col-sm-6 col-xl-3"><div class="card admin-card"><div class="card-body"><div class="text-secondary small"><?= e($label) ?></div><div class="display-6 fw-bold"><?= $counts[$key] ?></div></div></div></div><?php endforeach; ?><div class="col-sm-6 col-xl-3"><div class="card admin-card"><div class="card-body"><div class="text-secondary small">Toplam yazı</div><div class="display-6 fw-bold"><?= $total ?></div></div></div></div></div>
<div class="card admin-card"><div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3"><div><h2 class="h5 fw-bold mb-1">İçerik yönetimi</h2><p class="text-secondary mb-0">Blog yazılarını ekleyebilir, düzenleyebilir veya silebilirsin.</p></div><a href="yazi_ekle.php" class="btn btn-danger">+ Yeni yazı</a></div></div>
<?php require __DIR__ . '/partials/footer.php'; ?>
