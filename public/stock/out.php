<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$products = $pdo->query("SELECT id, name, quantity FROM products ORDER BY name")->fetchAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    $qty = (int)($_POST['quantity'] ?? 0);
    $note = trim($_POST['note'] ?? '');

    if ($qty <= 0) $errors[] = "Quantity must be > 0";
    if (!$product_id) $errors[] = "Select product.";

    // check available stock
    $stmt = $pdo->prepare("SELECT quantity FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $prod = $stmt->fetch();

    if (!$prod) $errors[] = "Product not found.";
    elseif ($qty > $prod['quantity']) $errors[] = "Insufficient stock.";

    if (empty($errors)) {
        $pdo->beginTransaction();
        $pdo->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?")->execute([$qty, $product_id]);
        $pdo->prepare("INSERT INTO stock_movements (product_id, changed_by, type, quantity, note) VALUES (?, ?, 'out', ?, ?)")
            ->execute([$product_id, $_SESSION['user_id'], $qty, $note]);
        $pdo->commit();
        header('Location: /inventory-sales/public/index.php');
        exit;
    }
}

include __DIR__ . '/../../includes/header.php';
?>
<h2>Stock Out</h2>
<?php foreach($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
<form method="post">
  <div class="form-row">
    <label>Product</label>
    <select name="product_id">
      <option value="">-- choose --</option>
      <?php foreach($products as $p): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?> (<?= $p['quantity'] ?>)</option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-row"><label>Quantity</label><input name="quantity" type="number" value="1" required></div>
  <div class="form-row"><label>Note</label><input name="note"></div>
  <button>Submit</button>
</form>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
