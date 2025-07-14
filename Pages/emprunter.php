<?php
session_start();
require("../Inclus/fonctions.php");

if (!isset($_SESSION['id_membre']) || !isset($_GET['id_objet'])) {
    header("Location: home.php");
    exit();
}

$id_objet = (int)$_GET['id_objet'];
$id_membre = $_SESSION['id_membre'];

$bdd = connecterBase();
$sql = "SELECT id_objet FROM S2_PROJET_FINAL_objets WHERE id_objet = ? AND id_membre != ? 
        AND NOT EXISTS (SELECT 1 FROM S2_PROJET_FINAL_emprunts WHERE id_objet = ? AND date_retour IS NULL)";
$stmt = mysqli_prepare($bdd, $sql);
mysqli_stmt_bind_param($stmt, "iii", $id_objet, $id_membre, $id_objet);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) == 0) {
    header("Location: home.php");
    exit();
}
mysqli_stmt_close($stmt);

$sql = "INSERT INTO S2_PROJET_FINAL_emprunts (id_objet, id_membre, date_emprunt) VALUES (?, ?, CURDATE())";
$stmt = mysqli_prepare($bdd, $sql);
mysqli_stmt_bind_param($stmt, "ii", $id_objet, $id_membre);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: home.php");
exit();
?>