<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

// fetch categories
$cats = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category_id = $_POST['category_id'] ?: null;
    $sku = trim($_POST['sku'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 0);
    $low_stock_threshold = (int)($_POST['low_stock_threshold'] ?? 5);

    if (!$name) $errors[] = "Product name required.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, category_id, sku, price, quantity, low_stock_threshold) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category_id, $sku, $price, $quantity, $low_stock_threshold]);
        header('Location: /inventory-sales/public/index.php');
        exit;
    }
}

include __DIR__ . '/../../includes/header.php';
?>
<h2>Add Product</h2>
<?php foreach($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
<form method="post">
  <div class="form-row">
    <label>Name</label>
    <input name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
  </div>
  <div class="form-row">
    <label>Category</label>
    <select name="category_id">
      <option value="">-- none --</option>
      <?php foreach($cats as $c): ?>
        <option value="<?= $c['id'] ?>" <?= (isset($_POST['category_id']) && $_POST['category_id']==$c['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($c['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-row"><label>SKU</label><input name="sku" value="<?= htmlspecialchars($_POST['sku'] ?? '') ?>"></div>
  <div class="form-row"><label>Price</label><input name="price" type="number" step="0.01" value="<?= htmlspecialchars($_POST['price'] ?? '0.00') ?>"></div>
  <div class="form-row"><label>Quantity</label><input name="quantity" type="number" value="<?= htmlspecialchars($_POST['quantity'] ?? '0') ?>"></div>
  <div class="form-row"><label>Low stock threshold</label><input name="low_stock_threshold" type="number" value="<?= htmlspecialchars($_POST['low_stock_threshold'] ?? '5') ?>"></div>
  <button>Add Product</button>
</form>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
