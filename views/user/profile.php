<?php 
require_once '../../controllers/UserController.php';
$userController = new UserController();

if(!isset($_SESSION['user'])) {
    header('Location: ../user/login.php');
    exit;
}

$user = $userController->getUserById($_SESSION['user']['id']);
?>

<?php require_once '../../layouts/header.php'; ?>

<div class="container">
    <h2>Mon profil</h2>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Informations personnelles</h5>
            
            <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Rôle :</strong> <?= ucfirst($user['role']) ?></p>
            
            <a href="edit_profile.php" class="btn btn-primary">Modifier mon profil</a>
        </div>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>