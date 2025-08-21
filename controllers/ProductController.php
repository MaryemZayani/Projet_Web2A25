<?php
require_once __DIR__ . '/../models/Product.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    // Afficher tous les produits
    public function index() {
        $products = $this->productModel->getAll();
        require_once __DIR__ . '/../views/front/products/index.php';
    }

    // Créer un nouveau produit (FORMULAIRE + TRAITEMENT)
    public function create() {
        $errors = [];
        
        // Si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation des données
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
                $data = [
                    'nom' => $nom,
                    'description' => $description,
                    'prix' => $prix,
                    'stock' => $stock
                ];

                if ($this->productModel->create($data)) {
                    header('Location: ?page=products&success=1');
                    exit;
                } else {
                    $errors[] = "Erreur lors de la création du produit";
                }
            }
        }

        // Afficher le formulaire
        require_once __DIR__ . '/../views/back/admin/products/create.php';
    }
}