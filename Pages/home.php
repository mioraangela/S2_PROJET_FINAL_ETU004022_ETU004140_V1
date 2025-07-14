<?php
require("../Inclus/fonctions.php");
session_start();

$categories = get_categories();
$selected_categorie = isset($_GET['categorie']) ? (int)$_GET['categorie'] : null;
$nom_objet = isset($_GET['nom_objet']) ? $_GET['nom_objet'] : null;
$disponible = isset($_GET['disponible']) ? true : false;
$objets = get_liste_objets($selected_categorie, $nom_objet, $disponible);
?>

<!DOCTYPE html>
<html>
<head>
    <title>
        Accueil - Prêt d'Objets
    </title>

    <link href="../Styles/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../Styles/home.css" rel="stylesheet">
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
        <h1 class="text-center mb-4">Liste des Objets</h1>

        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="categorie" class="form-label">Catégorie</label>
                    <select name="categorie" id="categorie" class="form-select">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $categorie): ?>
                            <option value="<?php echo $categorie['id_categorie']; ?>" <?php echo $selected_categorie == $categorie['id_categorie'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($categorie['nom_categorie']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="nom_objet" class="form-label">Nom de l'objet</label>
                    <input type="text" name="nom_objet" id="nom_objet" class="form-control" value="<?php echo htmlspecialchars($nom_objet ?? ''); ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" name="disponible" id="disponible" class="form-check-input" <?php echo $disponible ? 'checked' : ''; ?>>
                        <label for="disponible" class="form-check-label">Disponible uniquement</label>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Filtrer</button>
            </div>
        </form>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($objets as $objet): ?>
                <div class="col">
                    <div class="card objet-card h-100">
                        <img src="<?php echo $objet['nom_image'] ? 'Uploads/' . htmlspecialchars($objet['nom_image']) : 'images/default.jpg'; ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($objet['nom_objet']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($objet['nom_objet']); ?></h5>
                            <p class="card-text">
                                Catégorie: <?php echo htmlspecialchars($objet['nom_categorie']); ?><br>
                                Propriétaire: <?php echo htmlspecialchars($objet['nom_membre']); ?><br>
                                Statut: 
                                <?php if ($objet['date_retour']): ?>
                                    <span class="status-emprunte">Emprunté (retour: <?php echo $objet['date_retour']; ?>)</span>
                                <?php else: ?>
                                    <span class="status-disponible">Disponible</span>
                                <?php endif; ?>
                            </p>
                            <a href="objet.php?id_objet=<?php echo $objet['id_objet']; ?>" class="btn btn-primary">Voir la fiche</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="bg-light text-center py-3">
        <p>&copy; 004022-004140. Tous droits réservés.</p>
    </footer>

</body>
</html>