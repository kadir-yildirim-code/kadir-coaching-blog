<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(403); exit("Run this script from the command line.\n"); }
require_once __DIR__ . '/../db.php';
fwrite(STDOUT, 'Admin username: ');
$username = trim((string)fgets(STDIN));
fwrite(STDOUT, 'Admin email: ');
$email = trim((string)fgets(STDIN));
fwrite(STDOUT, 'Admin password (12+ characters): ');
$password = trim((string)fgets(STDIN));
if ($username === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12) {
    fwrite(STDERR, "Invalid input. Use a username, valid email, and password of at least 12 characters.\n");
    exit(1);
}
$hash = password_hash($password, PASSWORD_DEFAULT);
$statement = $conn->prepare('INSERT INTO users (USERNAME, MAIL, PASSWORD) VALUES (?, ?, ?)');
$statement->bind_param('sss', $username, $email, $hash);
$statement->execute();
echo "Admin account created successfully.\n";
