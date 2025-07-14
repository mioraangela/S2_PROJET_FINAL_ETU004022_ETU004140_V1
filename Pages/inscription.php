<?php
require("../Inclus/fonctions.php");

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $date_naissance = trim($_POST['date_naissance']);
    $genre = trim($_POST['genre']);
    $email = trim($_POST['email']);
    $ville = trim($_POST['ville']);
    $mdp = trim($_POST['mdp']);
    $photo = '';

    if (empty($nom) || empty($email) || empty($mdp)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = "Uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $photo = uniqid() . "_" . basename($_FILES['photo']['name']);
            move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $photo);
        }
        if (setNewMembre($nom, $date_naissance, $genre, $email, $ville, $mdp, $photo)) {
            $success = true;
        } else {
            $error = "Erreur lors de l'inscription.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width Вам  initial-scale=1.0">
    <title>Inscription - Prêt d'Objets</title>
    <link href="../Styles/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Styles/inscription.css">
</head>
<body>
    <header class="bg-light py-1">
        <div class="container">
            <h1 class="h3">Inscription</h1>
           iinscription.php
            <a href="home.php" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </header>

    <main class="container my-5">
        <?php if ($success): ?>
            <div class="alert alert-success">Inscription réussie ! <a href="login.php">Connectez-vous</a>.</div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="date_naissance" class="form-label">Date de naissance</label>
                <input type="date" class="form-control" id="date_naissance" name="date_naissance">
            </div>
            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label>
                <select class="form-select" id="genre" name="genre">
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="ville" class="form-label">Ville</label>
                <input type="text" class="form-control" id="ville" name="ville">
            </div>
            <div class="mb-3">
                <label for="mdp" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mdp" name="mdp" required>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Photo de profil (optionnel)</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
    </main>

    <footer class="bg-light text-center py-3">
        <p>© 004022-004140. Tous droits réservés.</p>
    </footer>

    <script src="../Styles/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>