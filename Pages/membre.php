<?php
require("../Inclus/fonctions.php");
session_start();

if (!isset($_GET['id_membre'])) {
    header("Location: home.php");
    exit;
}

$membre = get_membre($_GET['id_membre']);
$objets = get_objets_membre($_GET['id_membre']);
$categories = array_unique(array_column($objets, 'nom_categorie'));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - <?php echo htmlspecialchars($membre['nom']); ?></title>
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
                        <?php if (isset($_SESSION['id_membre'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="membre.php?id_membre=<?php echo $_SESSION['id_membre']; ?>">Mon Profil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="ajout_objet.php">Ajouter un objet</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Déconnexion</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Connexion</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="inscription.php">Inscription</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container my-5">
        <h1 class="text-center mb-4">Profil de <?php echo htmlspecialchars($membre['nom']); ?></h1>
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="<?php echo $membre['image_profil'] ? 'uploads/' . htmlspecialchars($membre['image_profil']) : 'images/default.jpg'; ?>" 
                     alt="Profil" class="img-fluid rounded-circle" style="max-width: 200px;">
            </div>
            <div class="col-md-8">
                <p><strong>Nom:</strong> <?php echo htmlspecialchars($membre['nom']); ?></p>
                <p><strong>Date de naissance:</strong> <?php echo $membre['date_naissance']; ?></p>
                <p><strong>Genre:</strong> <?php echo htmlspecialchars($membre['genre']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($membre['email']); ?></p>
                <p><strong>Ville:</strong> <?php echo htmlspecialchars($membre['ville']); ?></p>
            </div>
        </div>
        <h2 class="mt-5">Objets du membre</h2>
        <?php foreach ($categories as $categorie): ?>
            <h3><?php echo htmlspecialchars($categorie); ?></h3>
            <ul class="list-group mb-4">
                <?php foreach ($objets as $objet): ?>
                    <?php if ($objet['nom_categorie'] == $categorie): ?>
                        <li class="list-group-item">
                            <a href="objet.php?id_objet=<?php echo $objet['id_objet']; ?>">
                                <?php echo htmlspecialchars($objet['nom_objet']); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    </main>

    <footer class="bg-light text-center py-3">
        <p>&copy; 004022-004140. Tous droits réservés.</p>
    </footer>
</body>
</html>