<?php
// Vérifier si l'utilisateur est admin (ajoutez cette sécurité)
session_start();


// Inclure les modèles nécessaires
require_once __DIR__ . '/../../../../../config/database.php';
require_once __DIR__ . '/../../../../../models/Product.php';

// Initialiser les variables
$errors = [];
$success = false;

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et valider les données
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = (float)$_POST['prix'];
    $stock = (int)$_POST['stock'];

    // Validation
    if (empty($nom)) $errors[] = "Le nom est obligatoire";
    if (empty($description)) $errors[] = "La description est obligatoire";
    if ($prix <= 0) $errors[] = "Le prix doit être positif";
    if ($stock < 0) $errors[] = "Le stock ne peut pas être négatif";

    // Si pas d'erreurs, créer le produit
    if (empty($errors)) {
        $productModel = new Product();
        $data = [
            'nom' => $nom,
            'description' => $description,
            'prix' => $prix,
            'stock' => $stock
        ];

        if ($productModel->create($data)) {
            $success = true;
            // Réinitialiser le formulaire
            $_POST = [];
        } else {
            $errors[] = "Erreur lors de l'ajout dans la base de données";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../../../assests/css/style1.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <ul class="menu">
                <li class="active">
                    <a href="#">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="?page=products">
                        <i class="fas fa-box"></i>
                        <span>Produits</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Primary</span>
                <h2>Dashboard Admin</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" placeholder="Search">
                </div>
            </div>
        </div>

        <div class="container mt-4">
            <h1 class="mb-4">➕ Ajouter un Nouveau Produit</h1>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    ✅ Produit ajouté avec succès !
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="border p-4 rounded">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du produit *</label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description *</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="3" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="prix" class="form-label">Prix (€) *</label>
                        <input type="number" step="0.01" class="form-control" id="prix" 
                               name="prix" value="<?= htmlspecialchars($_POST['prix'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stock *</label>
                        <input type="number" class="form-control" id="stock" 
                               name="stock" value="<?= htmlspecialchars($_POST['stock'] ?? '0') ?>" required>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">➕ Créer le Produit</button>
                    <a href="?page=products" class="btn btn-secondary">← Retour aux produits</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>