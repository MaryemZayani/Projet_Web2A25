<?php
require_once __DIR__ . '/../../../../controllers/UserController.php';

$userController = new UserController();

$users = $userController->getAllUsers();

// Debug output
error_log("Users data: " . print_r($users, true));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashbord</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../../../../assests/css/style1.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <ul class="menu">
                <li class="active">
                    <a href="#">
                        <i class="fas fa-tachometer-alt">

                        </i>
                        <span>Dashbord</span>
                    </a>
                </li>
                <li>
                    <a href="index_user.html">
                        <i class="fas fa-user-alt">

                        </i>
                        <span>Profil</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-chart-bar">

                        </i>
                        <span>Statistics</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-briefcase">

                        </i>
                        <span>Carreers</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-question-circle">

                        </i>
                        <span>FAQ</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-star">

                        </i>
                        <span>Testimonials</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-cog">

                        </i>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="logout">
                    <a href="#">
                        <i class="fas fa-sign-out-alt">

                        </i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Priamry</span>
                <h2>Dashboard</h2>
            </div>
            <div class="user--info">
                <div class="searh--box">

               
             <i class="fa-solid fa-search">  </i>
             <input type="text" placeholder="Search">
            </div>

            </div>

           
        </div>
        <div class="card--container">
            <h3 class="main--title">Today's data</h3>
            <div class="card--wrapper">
                <div class="payment--card">
                    <div class="card--header">
                        <div class="amount">
                         <span class="title">
                            Payment amount
                         </span>
                         <span class="amount-value">
                            $500.00
                         </span>
                        </div>
                        <i class="fas fa-dollar-sign icon"></i>
                    </div>
                    <span class="card-detail">**** **** **** 3484</span>
                </div>

                <div class="payment--card">
                    <div class="card--header">
                        <div class="amount">
                         <span class="title">
                            Payment amount
                         </span>
                         <span class="amount-value">
                            $500.00
                         </span>
                        </div>
                        <i class="fas fa-dollar-sign icon"></i>
                    </div>
                    <span class="card-detail">**** **** **** 3484</span>
                </div>
                <div class="payment--card">
                    <div class="card--header">
                        <div class="amount">
                         <span class="title">
                            Payment amount
                         </span>
                         <span class="amount-value">
                            $500.00
                         </span>
                        </div>
                        <i class="fas fa-dollar-sign icon"></i>
                    </div>
                    <span class="card-detail">**** **** **** 3484</span>
                </div>
                <div class="payment--card">
                    <div class="card--header">
                        <div class="amount">
                         <span class="title">
                            Payment amount
                         </span>
                         <span class="amount-value">
                            $500.00
                         </span>
                        </div>
                        <i class="fas fa-dollar-sign icon"></i>
                    </div>
                    <span class="card-detail">**** **** **** 3484</span>
                </div>
                <div class="payment--card">
                    <div class="card--header">
                        <div class="amount">
                         <span class="title">
                            Payment amount
                         </span>
                         <span class="amount-value">
                            $500.00
                         </span>
                        </div>
                        <i class="fas fa-dollar-sign icon"></i>
                    </div>
                    <span class="card-detail">**** **** **** 3484</span>
                </div>
            </div>
        </div>
<div class="container">
    <h2>Gestion des utilisateurs</h2>
    
    <?php if(empty($users)): ?>
        <div class="alert alert-warning">
            Aucun utilisateur trouvé dans la base de données
        </div>
    <?php else: ?>
        <table class="table table-striped">
            <!-- Your table header -->
            <tbody>
                <?php foreach($users as $user): ?>
                    <!-- Your table rows -->
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <a href="add_user.php" class="btn btn-primary">Ajouter un utilisateur</a>
</div>

<div class="container">
    <h2>Gestion des utilisateurs</h2>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= ucfirst($user['role']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="mb-4">
    <a href="register_staff.php" class="btn btn-success">
        <i class="fas fa-user-plus"></i> Register New Staff
    </a>
</div>
</div>
    </div>
</body>

</html>