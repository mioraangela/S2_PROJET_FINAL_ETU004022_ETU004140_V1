<?php
session_start();
require("../Inclus/fonctions.php");

if (!isset($_SESSION['id_membre']) || !isset($_GET['id_objet']) || !isset($_GET['nom_image'])) {
    header("Location: home.php");
    exit();
}

$id_objet = (int)$_GET['id_objet'];
$nom_image = $_GET['nom_image'];

if (supprimerImage($id_objet, $nom_image, $_SESSION['id_membre'])) {
    header("Location: objet.php?id_objet=$id_objet");
} else {
    header("Location: objet.php?id_objet=$id_objet&error=suppression");
}
exit();
?>