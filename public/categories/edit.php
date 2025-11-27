<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$cat = $stmt->fetch();
if (!$cat) { header('Location: index.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if (!$name) $errors[] = "Name required.";
    if (empty($errors)) {
        $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?")->execute([$name, $id]);
        header('Location: index.php');
        exit;
    }
}

include __DIR__ . '/../../includes/header.php';
?>
<h2>Edit Category</h2>
<?php foreach($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
<form method="post">
  <div class="form-row"><label>Name</label><input name="name" value="<?= htmlspecialchars($_POST['name'] ?? $cat['name']) ?>" required></div>
  <button>Save</button>
</form>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
