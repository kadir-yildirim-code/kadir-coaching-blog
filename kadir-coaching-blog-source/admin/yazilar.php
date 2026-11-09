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
$posts = $conn->query('SELECT ID, title, category, created_at, view_count FROM posts ORDER BY ID DESC')->fetch_all(MYSQLI_ASSOC);
$categories = [1 => 'Antrenman', 2 => 'Bilgi', 3 => 'Paketler'];
require __DIR__ . '/partials/header.php';
?>
<?php if ($message !== ''): ?><div class="alert alert-info"><?= e($message) ?></div><?php endif; ?>
<div class="card admin-card"><div class="card-body"><div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 fw-bold mb-0">Yayınlanan yazılar</h2><a class="btn btn-danger btn-sm" href="yazi_ekle.php">Yeni yazı</a></div><div class="table-responsive"><table class="table align-middle"><thead><tr><th>ID</th><th>Başlık</th><th>Kategori</th><th>Görüntülenme</th><th>Tarih</th><th>İşlemler</th></tr></thead><tbody><?php foreach ($posts as $post): ?><tr><td><?= (int)$post['ID'] ?></td><td><?= e($post['title']) ?></td><td><?= e($categories[(int)$post['category']] ?? 'Genel') ?></td><td><?= (int)$post['view_count'] ?></td><td><?= e(date('d.m.Y', strtotime($post['created_at']))) ?></td><td><div class="d-flex gap-2"><a class="btn btn-outline-primary btn-sm" href="yazi_duzenle.php?id=<?= (int)$post['ID'] ?>">Düzenle</a><form method="post" onsubmit="return confirm('Bu yazıyı silmek istediğine emin misin?');"><?= csrf_field() ?><input type="hidden" name="delete_id" value="<?= (int)$post['ID'] ?>"><button class="btn btn-outline-danger btn-sm" type="submit">Sil</button></form></div></td></tr><?php endforeach; ?></tbody></table></div></div></div>
<?php require __DIR__ . '/partials/footer.php'; ?>
