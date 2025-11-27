<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
include __DIR__ . '/../../includes/header.php';
?>
<h2>Categories</h2>
<p><a href="add.php">Add Category</a></p>
<table>
  <tr><th>ID</th><th>Name</th><th>Actions</th></tr>
  <?php foreach($cats as $c): ?>
  <tr>
    <td><?= $c['id'] ?></td>
    <td><?= htmlspecialchars($c['name']) ?></td>
    <td>
      <a href="edit.php?id=<?= $c['id'] ?>">Edit</a> |
      <a href="delete.php?id=<?= $c['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
