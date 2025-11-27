<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    // Setting product.category_id to NULL is handled by FK in DB (ON DELETE SET NULL)
    $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
}
header('Location: index.php');
exit;
