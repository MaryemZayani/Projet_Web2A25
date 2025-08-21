<?php require_once __DIR__ . '/../../../layouts/admin/header.php'; ?>

<div class="container">
    <h1 class="my-4">Gestion des Produits</h1>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Opération réussie!</div>
    <?php endif; ?>
    
    <a href="create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Ajouter un produit
    </a>
    
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= htmlspecialchars($product['nom']) ?></td>
                <td><?= ucfirst($product['categorie']) ?></td>
                <td><?= number_format($product['prix'], 2) ?> €</td>
                <td><?= $product['stock'] ?></td>
                <td>
                    <a href="edit/<?= $product['id'] ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="delete/<?= $product['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       onclick="return confirm('Supprimer ce produit?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../../layouts/admin/footer.php'; ?>