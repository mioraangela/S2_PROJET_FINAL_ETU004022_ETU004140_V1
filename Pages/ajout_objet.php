<?php
session_start();
require("../Inclus/fonctions.php");

if (!isset($_SESSION['id_membre'])) {
    header("Location: login.php");
    exit();
}

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_objet = trim($_POST['nom_objet']);
    $id_categorie = (int)$_POST['id_categorie'];
    
    if (empty($nom_objet) || $id_categorie <= 0) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } elseif (empty($_FILES['images']['name'][0])) {
        $error = "Veuillez uploader au moins une image.";
    } else {
        $id_membre = $_SESSION['id_membre'];
        $id_objet = ajouterObjet($nom_objet, $id_categorie, $id_membre, $_FILES['images']);
        if ($id_objet) {
            $success = true;
        } else {
            $error = "Erreur lors de l'ajout de l'objet.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Objet - Prêt d'Objets</title>
    <link href="../Styles/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../Styles/ajout_objet.css" rel="stylesheet">
    <style>
        .form-container { max-width: 600px; margin: 50px auto; }
    </style>
</head>
<body>
    <header class="bg-light py-3">
        <div class="container">
            <h1 class="h3">Ajouter un Nouvel Objet</h1>
            <a href="home.php" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </header>

    <main class="container form-container">
        <?php if ($success): ?>
            <div class="alert alert-success">Objet ajouté avec succès ! <a href="home.php">Retour à la liste</a></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nom_objet" class="form-label">Nom de l'objet</label>
                <input type="text" class="form-control" id="nom_objet" name="nom_objet" required>
            </div>
            <div class="mb-3">
                <label for="id_categorie" class="form-label">Catégorie</label>
                <select class="form-select" id="id_categorie" name="id_categorie" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <?php
                    $categories = get_categories();
                    foreach ($categories as $categorie) {
                        echo "<option value='{$categorie['id_categorie']}'>" . htmlspecialchars($categorie['nom_categorie']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="images" class="form-label">Images (la première sera l'image principale)</label>
                <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter l'objet</button>
        </form>
    </main>

    <footer class="bg-light text-center py-3">
        <p>© 004022-004140. Tous droits réservés.</p>
    </footer>

    <script src="../Styles/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>