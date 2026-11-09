<?php
require_once __DIR__ . '/../db.php';
if (!empty($_SESSION['user_id'])) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_is_valid()) {
        $error = 'Oturum doğrulaması başarısız. Sayfayı yenileyip tekrar deneyin.';
    } else {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = (string)($_POST['password'] ?? '');
        if (!$email || $password === '') {
            $error = 'Geçerli bir e-posta ve parola girin.';
        } else {
            $statement = $conn->prepare('SELECT ID, USERNAME, PASSWORD FROM users WHERE MAIL = ? LIMIT 1');
            $statement->bind_param('s', $email);
            $statement->execute();
            $user = $statement->get_result()->fetch_assoc();
            if ($user && password_verify($password, $user['PASSWORD'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int)$user['ID'];
                $_SESSION['username'] = $user['USERNAME'];
                header('Location: index.php');
                exit;
            }
            $error = 'E-posta veya parola hatalı.';
        }
    }
}
?>
<!doctype html><html lang="tr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Yönetici Girişi | Kadir Coaching</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/style.css"></head><body class="min-vh-100 d-flex align-items-center" style="background:linear-gradient(135deg,#202b46,#9e362d)"><main class="container"><div class="row justify-content-center"><div class="col-sm-10 col-md-7 col-lg-5"><div class="card admin-card"><div class="card-body p-4 p-lg-5"><div class="text-center mb-4"><span class="navbar-brand-mark mb-3">KC</span><h1 class="h4 fw-bold">Kadir Coaching</h1><p class="text-secondary mb-0">Yönetici Paneli</p></div><?php if ($error !== ''): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?><form method="post" autocomplete="on"><?= csrf_field() ?><div class="mb-3"><label class="form-label" for="email">E-posta</label><input class="form-control" type="email" id="email" name="email" required autocomplete="username"></div><div class="mb-4"><label class="form-label" for="password">Parola</label><input class="form-control" type="password" id="password" name="password" required autocomplete="current-password"></div><button class="btn btn-danger w-100" type="submit">Giriş yap</button></form><div class="text-center mt-4"><a href="../index.php" class="text-decoration-none">← Siteye dön</a></div></div></div></div></div></main></body></html>
