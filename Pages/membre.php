<?php
session_start();
require("../Inclus/fonctions.php");

if (!isset($_SESSION['id_membre']) || !isset($_GET['id_membre'])) {
    header("Location: login.php");
    exit();
}

$id_membre = (int)$_GET['id_membre'];
$bdd = connecterBase();
$sql = "SELECT * FROM S2_PROJET_FINAL_membres WHERE id_membre = ?";
$stmt = mysqli_prepare($bdd, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_membre);
mysqli_stmt_execute($stmt);
$membre = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$sql = "SELECT o.*, c.nom_categorie, 
        (SELECT nom_image FROM S2_PROJET_FINAL_objets_images i WHERE i.id_objet = o.id_objet ORDER BY i.id_image LIMIT 1) as nom_image
        FROM S2_PROJET_FINAL_objets o 
        JOIN S2_PROJET_FINAL_categories_objets c ON o.id_categorie = c.id_categorie 
        WHERE o.id_membre = ? 
        ORDER BY c.nom_categorie";
$stmt = mysqli_prepare($bdd, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_membre);
mysqli_stmt_execute($stmt);
$objects = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Group objects by category
$objects_by_category = [];
foreach ($objects as $obj) {
    $objects_by_category[$obj['nom_categorie']][] = $obj;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Membre - Prêt d'Objets</title>
    <link href="../Styles/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-light py-3">
        <div class="container">
            <h1 class="h3">Profil de <?php echo htmlspecialchars($membre['nom']); ?></h1>
            <a href="home.php" class="btn btn-primary">Retour</a>
        </div>
    </header>
    <main class="container my-4">
        <h2>Informations du Membre</h2>
        <p><strong>Nom:</strong> <?php echo htmlspecialchars($membre['nom']); ?></p>
        <p><strong>Date de naissance:</strong> <?php echo htmlspecialchars($membre['date_naissance']); ?></p>
        <p><strong>Genre:</strong> <?php echo htmlspecialchars($membre['genre']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($membre['email']); ?></p>
        <p><strong>Ville:</strong> <?php echo htmlspecialchars($membre['ville']); ?></p>
        <img src="<?php echo $membre['image_profil'] ? 'Uploads/' . htmlspecialchars($membre['image_profil']) : '../images/default.jpg'; ?>" 
             class="img-fluid mb-3" alt="Photo de profil" style="max-width: 200px;">

        <h2>Mes Objets</h2>
        <?php if (empty($objects_by_category)): ?>
            <p>Aucun objet enregistré.</p>
        <?php else: ?>
            <?php foreach ($objects_by_category as $categorie => $objs): ?>
                <h3><?php echo htmlspecialchars($categorie); ?></h3>
                <div class="row">
                    <?php foreach ($objs as $obj): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="<?php echo $obj['nom_image'] ? 'Uploads/' . htmlspecialchars($obj['nom_image']) : '../images/default.jpg'; ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($obj['nom_objet']); ?>" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($obj['nom_objet']); ?></h5>
                                    <a href="objet.php?id_objet=<?php echo $obj['id_objet']; ?>" class="btn btn-primary">Voir détails</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <footer class="bg-light text-center py-3">
        <p>© 004022-004140. Tous droits réservés.</p>
    </footer>

    <script src="../Styles/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>