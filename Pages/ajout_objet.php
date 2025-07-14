<?php
require("../Inclus/fonctions.php");
session_start();

if (!isset($_SESSION['id_membre'])) {
    header("Location: login.php");
    exit;
}

$categories = get_categories();
$erreur = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_objet = $_POST['nom_objet'] ?? '';
    $id_categorie = $_POST['id_categorie'] ?? '';
    $images = [];

    if (empty($nom_objet) || empty($id_categorie)) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        if (!empty($_FILES['images']['name'][0])) {
            $upload_dir = 'uploads/';
            foreach ($_FILES['images']['name'] as $key => $name) {
                if ($_FILES['images']['error'][$key] == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['images']['tmp_name'][$key];
                    $new_name = uniqid() . '_' . basename($name);
                    if (move_uploaded_file($tmp_name, $upload_dir . $new_name)) {
                        $images[] = $new_name;
                    }
                }
            }
        }

        if (ajouter_objet($nom_objet, $id_categorie, $_SESSION['id_membre'], $images)) {
            $success = "Objet ajouté avec succès !";
        } else {
            $erreur = "Erreur lors de l'ajout de l'objet.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Objet</title>
    <link href="../Styles/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../Styles/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Prêt d'Objets</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="home.php">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="membre.php?id_membre=<?php echo $_SESSION['id_membre']; ?>">Mon Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ajout_objet.php">Ajouter un objet</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Déconnexion</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container my-5">
        <h1 class="text-center mb-4">Ajouter un nouvel objet</h1>
        <?php if ($erreur): ?>
            <div class="alert alert-danger"><?php echo $erreur; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nom_objet" class="form-label">Nom de l'objet</label>
                <input type="text" name="nom_objet" id="nom_objet" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="id_categorie" class="form-label">Catégorie</label>
                <select name="id_categorie" id="id_categorie" class="form-select" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?php echo $categorie['id_categorie']; ?>">
                            <?php echo htmlspecialchars($categorie['nom_categorie']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="images" class="form-label">Images</label>
                <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </main>

    <footer class="bg-light text-center py-3">
        <p>&copy; 004022-004140. Tous droits réservés.</p>
    </footer>

</body>
</html>