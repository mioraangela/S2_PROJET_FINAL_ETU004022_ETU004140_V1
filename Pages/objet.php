<?php
session_start();
require("../Inclus/fonctions.php");

if (!isset($_GET['id_objet'])) {
    header("Location: home.php");
    exit();
}

$id_objet = (int)$_GET['id_objet'];
$bdd = connecterBase();
$sql = "SELECT o.*, c.nom_categorie, m.nom as proprietaire 
        FROM S2_PROJET_FINAL_objets o 
        JOIN S2_PROJET_FINAL_categories_objets c ON o.id_categorie = c.id_categorie 
        JOIN S2_PROJET_FINAL_membres m ON o.id_membre = m.id_membre 
        WHERE o.id_objet = ?";
$stmt = mysqli_prepare($bdd, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_objet);
mysqli_stmt_execute($stmt);
$objet = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$sql = "SELECT nom_image FROM S2_PROJET_FINAL_objets_images WHERE id_objet = ? ORDER BY id_image";
$stmt = mysqli_prepare($bdd, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_objet);
mysqli_stmt_execute($stmt);
$images = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

$sql = "SELECT e.*, m.nom FROM S2_PROJET_FINAL_emprunts e 
        JOIN S2_PROJET_FINAL_membres m ON e.id_membre = m.id_membre 
        WHERE e.id_objet = ? ORDER BY e.date_emprunt DESC";
$stmt = mysqli_prepare($bdd, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_objet);
mysqli_stmt_execute($stmt);
$emprunts = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['duree']) && isset($_SESSION['id_membre'])) {
    $duree = (int)$_POST['duree'];
    if ($duree <= 0) {
        $error = "Veuillez sélectionner une durée valide.";
    } elseif (emprunterObjet($id_objet, $_SESSION['id_membre'], $duree)) {
        $success = true;
    } else {
        $error = "Erreur lors de l'emprunt ou objet non disponible.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Objet - Prêt d'Objets</title>
    <link href="../Styles/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-light py-3">
        <div class="container">
            <h1 class="h3">Fiche de l'Objet</h1>
            <a href="home.php" class="btn btn-primary">Retour</a>
        </div>
    </header>
    <main class="container my-4">
        <?php if ($success): ?>
            <div class="alert alert-success">Objet emprunté avec succès !</div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <h2><?php echo htmlspecialchars($objet['nom_objet']); ?></h2>
        <p>Catégorie: <?php echo htmlspecialchars($objet['nom_categorie']); ?></p>
        <p>Propriétaire: <?php echo htmlspecialchars($objet['proprietaire']); ?></p>

        <h3>Images</h3>
        <div class="row">
            <?php if (empty($images)): ?>
                <div class="col-md-4 mb-3">
                    <img src="../images/default.jpg" class="img-fluid" alt="Image par défaut" style="max-height: 300px;">
                </div>
            <?php else: ?>
                <?php foreach ($images as $index => $image): ?>
                    <div class="col-md-4 mb-3">
                        <img src="Uploads/<?php echo htmlspecialchars($image['nom_image']); ?>" class="img-fluid" alt="Image <?php echo $index + 1; ?>">
                        <?php if (isset($_SESSION['id_membre']) && $_SESSION['id_membre'] == $objet['id_membre']): ?>
                            <a href="supprimer_image.php?id_objet=<?php echo $id_objet; ?>&nom_image=<?php echo urlencode($image['nom_image']); ?>" 
                               class="btn btn-danger btn-sm mt-2" 
                               onclick="return confirm('Voulez-vous vraiment supprimer cette image ?');">Supprimer</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <h3>Historique des emprunts</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Emprunteur</th>
                    <th>Date d'emprunt</th>
                    <th>Date de retour</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($emprunts)): ?>
                    <tr>
                        <td colspan="3">Aucun emprunt enregistré.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($emprunts as $emprunt): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($emprunt['nom']); ?></td>
                            <td><?php echo htmlspecialchars($emprunt['date_emprunt']); ?></td>
                            <td><?php echo $emprunt['date_retour'] ? htmlspecialchars($emprunt['date_retour']) : 'Non retourné'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php
        $sql = "SELECT COUNT(*) FROM S2_PROJET_FINAL_emprunts WHERE id_objet = ? AND date_retour IS NULL";
        $stmt = mysqli_prepare($bdd, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_objet);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $is_emprunte = mysqli_fetch_row($result)[0] > 0;
        mysqli_stmt_close($stmt);
        ?>
        <?php if (!$is_emprunte && isset($_SESSION['id_membre']) && $_SESSION['id_membre'] != $objet['id_membre']): ?>
            <h3>Emprunter cet objet</h3>
            <form method="POST">
                <div class="mb-3">
                    <label for="duree" class="form-label">Durée de l'emprunt (jours)</label>
                    <input type="number" class="form-control" id="duree" name="duree" min="1" max="30" required>
                </div>
                <button type="submit" class="btn btn-success">Emprunter</button>
            </form>
        <?php endif; ?>
    </main>

    <footer class="bg-light text-center py-3">
        <p>© 004022-004140. Tous droits réservés.</p>
    </footer>

    <script src="../Styles/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>