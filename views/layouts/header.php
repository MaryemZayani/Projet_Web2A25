<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patisserie Management</title>
    <link rel="stylesheet" href="/css/styles.css">  <!-- Assume a basic CSS file -->
</head>
<body>
    <nav>
        <?php if (isset($_SESSION['user'])): ?>
            <p>Welcome, <?= htmlspecialchars($_SESSION['user']['nom']) ?> (<?= $_SESSION['user']['role'] ?>)</p>
            <a href="/logout">Logout</a>
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <a href="/users">Manage Users</a>
            <?php endif; ?>
        <?php endif; ?>
    </nav>