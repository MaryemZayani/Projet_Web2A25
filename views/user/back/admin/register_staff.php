<?php
session_start();
$errors = [];

require_once __DIR__ . '/../../../../controllers/UserController.php';
$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validate inputs
        if (empty($_POST['nom'])) {
            throw new Exception("Full name is required");
        }
        
        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Valid email is required");
        }
        
        if (empty($_POST['password']) || strlen($_POST['password']) < 8) {
            throw new Exception("Password must be at least 8 characters");
        }
        
        if (empty($_POST['role']) || !in_array($_POST['role'], ['admin', 'preparateur'])) {
            throw new Exception("Please select a valid role");
        }
        
        if (!isset($_POST['terms'])) {
            throw new Exception("You must accept the terms and conditions");
        }

        $data = [
            'nom' => htmlspecialchars(trim($_POST['nom'])),
            'email' => filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL),
            'password' => trim($_POST['password']),
            'role' => $_POST['role']
        ];

        if ($userController->register($data)) {
            $_SESSION['success'] = "Registration successful!";
            header('Location: /patisserie/views/user/login.php');
            exit;
        }
        
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration</title>
    <link rel="stylesheet" href="../../../../assests/css/style2.css">
</head>
<body>
  <div class="wrapper">
    <h2>Staff Registration</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="input-box">
            <input type="text" name="nom" placeholder="Full Name" required
                   value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
        </div>
        
        <div class="input-box">
            <input type="email" name="email" placeholder="Email" required
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        
        <div class="input-box">
            <input type="password" name="password" placeholder="Password (min 8 chars)" minlength="8" required>
        </div>
        <div class="input-box">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        </div>
        
        <div class="input-box">
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="admin" <?= ($_POST['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrator</option>
                <option value="preparateur" <?= ($_POST['role'] ?? '') === 'preparateur' ? 'selected' : '' ?>>Preparateur</option>
            </select>
        </div>
        
        <div class="policy">
            <input type="checkbox" name="terms" id="terms" <?= isset($_POST['terms']) ? 'checked' : '' ?>>
            <label for="terms">I accept all terms & conditions</label>
        </div>
        
        <div class="input-box button">
            <input type="submit" value="Register">
            <a href="/patisserie/views/user/login.php"></a>
        </div>
        
        <div class="text">
            <h3>Already have an account? <a href="/patisserie/views/user/login.php">Login now</a></h3>
        </div>
    </form>
  </div>
</body>
</html>