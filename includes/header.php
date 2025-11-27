<?php
// includes/header.php
if (session_status() == PHP_SESSION_NONE) session_start();
$user = null;
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/auth.php';
    $user = current_user();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Inventory & Sales Management</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
  <h1>Inventory & Sales Management</h1>
  <nav>
    <?php if ($user): ?>
      Hello, <strong><?= htmlspecialchars($user['username']) ?></strong> |
      <a href="/inventory-sales/public/index.php">Dashboard</a> |
      <a href="/inventory-sales/public/products/add.php">Add Product</a> |
      <a href="/inventory-sales/public/categories/index.php">Categories</a> |
      <a href="/inventory-sales/public/stock/in.php">Stock In</a> |
      <a href="/inventory-sales/public/stock/out.php">Stock Out</a> |
      <a href="/inventory-sales/public/logout.php">Logout</a>
    <?php else: ?>
      <a href="/inventory-sales/public/login.php">Login</a> |
      <a href="/inventory-sales/public/register.php">Register</a>
    <?php endif; ?>
  </nav>
  <hr>
</header>
<main>
