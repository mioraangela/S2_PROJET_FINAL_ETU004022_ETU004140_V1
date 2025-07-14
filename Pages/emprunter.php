<?php
session_start();
require("../Inclus/fonctions.php");

if (!isset($_SESSION['id_membre']) || !isset($_GET['id_objet']) || !isset($_GET['duree'])) {
    header("Location: home.php");
    exit();
}

$id_objet = (int)$_GET['id_objet'];
$id_membre = $_SESSION['id_membre'];
$duree = (int)$_GET['duree'];

if ($duree <= 0) {
    header("Location: objet.php?id_objet=$id_objet&error=duree");
    exit();
}

if (emprunterObjet($id_objet, $id_membre, $duree)) {
    header("Location: objet.php?id_objet=$id_objet&success=1");
} else {
    header("Location: objet.php?id_objet=$id_objet&error=emprunt");
}
exit();
?>