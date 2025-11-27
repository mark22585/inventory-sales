<?php
require_once __DIR__ . '/../includes/config.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else {
        $errors[] = "Invalid login.";
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Login</title></head><body>
<h2>Login</h2>
<?php foreach($errors as $e) echo "<p style='color:red'>".$e."</p>"; ?>
<form method="post">
  <label>Username<input name="username"></label><br>
  <label>Password<input type="password" name="password"></label><br>
  <button>Login</button>
</form>
</body></html>
