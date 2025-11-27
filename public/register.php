<?php
require_once __DIR__ . '/../includes/config.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $errors[] = "Enter username and password.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $errors[] = "Username taken.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
            $stmt->execute([$username, $hash]);
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <!-- Adjust path if your register.php location differs -->
  <link rel="stylesheet" href="assets/register.css">
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
  <main class="auth-page">
    <div class="auth-card" role="region" aria-labelledby="reg-title">
      <h2 id="reg-title" class="title">Register Admin</h2>

      <?php if (!empty($errors)): ?>
        <div class="errors" aria-live="polite">
          <?php foreach($errors as $e): ?>
            <p class="error-text"><?= htmlspecialchars($e) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="post" class="auth-form" autocomplete="off" novalidate>
        <label class="field">
          <span class="label-text">Username</span>
          <input name="username" class="input" required>
        </label>

        <label class="field">
          <span class="label-text">Password</span>
          <input type="password" name="password" class="input" required>
        </label>

        <div class="actions">
          <button class="btn" type="submit">Register</button>
          <a class="link" href="login.php">Back to login</a>
        </div>
      </form>
    </div>

    <div class="decor-bg" aria-hidden="true"></div>
  </main>
</body>
</html>
