<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

// Fetch products and low stock count
$stmt = $pdo->query("
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.name
");
$products = $stmt->fetchAll();

$lowCount = $pdo->query("SELECT COUNT(*) FROM products WHERE quantity <= low_stock_threshold")->fetchColumn();

include __DIR__ . '/../includes/header.php';
?>
<h2>Dashboard</h2>
<p><strong>Low-stock alerts:</strong> <?= (int)$lowCount ?></p>

<table>
  <tr><th>ID</th><th>Name</th><th>Category</th><th>Qty</th><th>Low Threshold</th><th>Actions</th></tr>
  <?php foreach ($products as $p): ?>
  <tr class="<?= ($p['quantity'] <= $p['low_stock_threshold']) ? 'low-stock' : '' ?>">
    <td><?= $p['id'] ?></td>
    <td><?= htmlspecialchars($p['name']) ?></td>
    <td><?= htmlspecialchars($p['category_name'] ?? 'None') ?></td>
    <td><?= $p['quantity'] ?></td>
    <td><?= $p['low_stock_threshold'] ?></td>
    <td>
      <a href="products/view.php?id=<?= $p['id'] ?>">View</a> |
      <a href="products/edit.php?id=<?= $p['id'] ?>">Edit</a> |
      <a href="products/delete.php?id=<?= $p['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
    </td>
  </tr>
  <?php endforeach; ?>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
