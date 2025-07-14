<?php
require("../Inclus/fonctions.php");
session_start();

if (!isset($_GET['id_objet'])) {
    header("Location: home.php");
    exit;
}

$objet = get_objet($_GET['id_objet']);
$images = get_images_objet($_GET['id_objet']);
$emprunts = get_historique_emprunts($_GET['id_objet']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Objet - <?php echo htmlspecialchars($objet['nom_objet']); ?></title>
    <link href="../Styles/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../Styles/bootstrap/js/bootstrap.bundle.min.js"></script>
    <style>
        .main-image img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        .thumbnail img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            cursor: pointer;
        }
    </style>
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
        <h1 class="text-center mb-4"><?php echo htmlspecialchars($objet['nom_objet']); ?></h1>
        <div class="row">
            <div class="col-md-6">
                <div class="main-image">
                    <img src="<?php echo $images ? 'uploads/' . htmlspecialchars($images[0]) : 'images/default.jpg'; ?>" 
                         alt="<?php echo htmlspecialchars($objet['nom_objet']); ?>">
                </div>
                <div class="d-flex flex-wrap mt-3">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="thumbnail me-2">
                            <img src="uploads/<?php echo htmlspecialchars($image); ?>" 
                                 alt="Image <?php echo $index + 1; ?>"
                                 onclick="document.querySelector('.main-image img').src = this.src;">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-6">
                <p><strong>Catégorie:</strong> <?php echo htmlspecialchars($objet['nom_categorie']); ?></p>
                <p><strong>Propriétaire:</strong> <?php echo htmlspecialchars($objet['nom_membre']); ?></p>
                <h3>Historique des emprunts</h3>
                <?php if ($emprunts): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date Emprunt</th>
                                <th>Date Retour</th>
                                <th>Emprunteur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($emprunts as $emprunt): ?>
                                <tr>
                                    <td><?php echo $emprunt['date_emprunt']; ?></td>
                                    <td><?php echo $emprunt['date_retour'] ?? 'Non retourné'; ?></td>
                                    <td><?php echo htmlspecialchars($emprunt['nom']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucun emprunt pour cet objet.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="bg-light text-center py-3">
        <p>&copy; 004022-004140. Tous droits réservés.</p>
    </footer>
</body>
</html>