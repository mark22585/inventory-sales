<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if (!$name) $errors[] = "Name required.";
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        header('Location: index.php');
        exit;
    }
}

include __DIR__ . '/../../includes/header.php';
?>
<h2>Add Category</h2>
<?php foreach($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
<form method="post">
  <div class="form-row"><label>Name</label><input name="name" required></div>
  <button>Add</button>
</form>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
