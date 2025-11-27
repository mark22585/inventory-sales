<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: /inventory-sales/public/index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) {
    header('Location: /inventory-sales/public/index.php');
    exit;
}

include __DIR__ . '/../../includes/header.php';
?>
<h2>Product Details</h2>
<p><strong>Name:</strong> <?= htmlspecialchars($p['name']) ?></p>
<p><strong>Category:</strong> <?= htmlspecialchars($p['category_name'] ?? 'None') ?></p>
<p><strong>SKU:</strong> <?= htmlspecialchars($p['sku']) ?></p>
<p><strong>Price:</strong> <?= htmlspecialchars($p['price']) ?></p>
<p><strong>Quantity:</strong> <?= htmlspecialchars($p['quantity']) ?></p>
<p><strong>Low threshold:</strong> <?= htmlspecialchars($p['low_stock_threshold']) ?></p>
<p><a href="/inventory-sales/public/index.php">Back to Dashboard</a></p>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
