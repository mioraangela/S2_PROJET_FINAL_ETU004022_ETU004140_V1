<?php
require("connexion.php");

function connecterMembre($email, $password) {
    $bdd = connecterBase();

    $sql = "SELECT * 
            FROM v_connexion_membre
            WHERE email = '%s'
            AND mdp = '%s'";
    
    $sql = sprintf($sql, mysqli_real_escape_string($bdd, $email), mysqli_real_escape_string($bdd, $password));
    $req = mysqli_query($bdd, $sql);
    $result = array();

    while ($news = mysqli_fetch_assoc($req)) {
        $result[] = $news;
    }

    mysqli_free_result($req);
    return $result;
}

function setNewMembre($nom, $date_naissance, $genre, $email, $ville, $mdp, $photo) {
    $bdd = connecterBase();

    $sql = "INSERT INTO S2_PROJET_FINAL_membres(nom, date_naissance, genre, email, ville, mdp, image_profil) 
            VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s')";

    $sql = sprintf($sql, 
        mysqli_real_escape_string($bdd, $nom),
        mysqli_real_escape_string($bdd, $date_naissance),
        mysqli_real_escape_string($bdd, $genre),
        mysqli_real_escape_string($bdd, $email),
        mysqli_real_escape_string($bdd, $ville),
        mysqli_real_escape_string($bdd, $mdp),
        mysqli_real_escape_string($bdd, $photo)
    );
    $req = mysqli_query($bdd, $sql);
    return $req;
}

function get_liste_emprunt() {
    $bdd = connecterBase();

    $sql = "SELECT * FROM v_emprunt";
    $req = mysqli_query($bdd, $sql);
    $result = array();

    while ($news = mysqli_fetch_assoc($req)) {
        $result[] = $news;
    }

    mysqli_free_result($req);
    return $result;
}

function get_liste_objets($categorie = null, $nom_objet = null, $disponible = false) {
    $bdd = connecterBase();

    $sql = "SELECT o.id_objet, o.nom_objet, c.nom_categorie, m.nom AS nom_membre, 
                   i.nom_image, e.date_retour
            FROM S2_PROJET_FINAL_objets o
            LEFT JOIN S2_PROJET_FINAL_categories_objets c ON o.id_categorie = c.id_categorie
            LEFT JOIN S2_PROJET_FINAL_membres m ON o.id_membre = m.id_membre
            LEFT JOIN S2_PROJET_FINAL_objets_images i ON o.id_objet = i.id_objet 
                AND i.id_image = (SELECT MIN(id_image) FROM S2_PROJET_FINAL_objets_images WHERE id_objet = o.id_objet)
            LEFT JOIN S2_PROJET_FINAL_emprunts e ON o.id_objet = e.id_objet AND e.date_retour IS NULL
            WHERE 1=1";
    
    if ($categorie) {
        $sql .= " AND o.id_categorie = " . (int)$categorie;
    }
    if ($nom_objet) {
        $sql .= " AND o.nom_objet LIKE '%" . mysqli_real_escape_string($bdd, $nom_objet) . "%'";
    }
    if ($disponible) {
        $sql .= " AND e.id_emprunt IS NULL";
    }

    $req = mysqli_query($bdd, $sql);
    $result = array();

    while ($news = mysqli_fetch_assoc($req)) {
        $result[] = $news;
    }

    mysqli_free_result($req);
    return $result;
}

function get_categories() {
    $bdd = connecterBase();

    $sql = "SELECT * FROM S2_PROJET_FINAL_categories_objets";
    $req = mysqli_query($bdd, $sql);
    $result = array();

    while ($news = mysqli_fetch_assoc($req)) {
        $result[] = $news;
    }

    mysqli_free_result($req);
    return $result;
}

function get_objet($id_objet) {
    $bdd = connecterBase();

    $sql = "SELECT o.id_objet, o.nom_objet, c.nom_categorie, m.nom AS nom_membre
            FROM S2_PROJET_FINAL_objets o
            JOIN S2_PROJET_FINAL_categories_objets c ON o.id_categorie = c.id_categorie
            JOIN S2_PROJET_FINAL_membres m ON o.id_membre = m.id_membre
            WHERE o.id_objet = %d";
    
    $sql = sprintf($sql, (int)$id_objet);
    $req = mysqli_query($bdd, $sql);
    $result = mysqli_fetch_assoc($req);
    mysqli_free_result($req);
    return $result;
}

function get_images_objet($id_objet) {
    $bdd = connecterBase();

    $sql = "SELECT nom_image FROM S2_PROJET_FINAL_objets_images WHERE id_objet = %d";
    $sql = sprintf($sql, (int)$id_objet);
    $req = mysqli_query($bdd, $sql);
    $result = array();

    while ($news = mysqli_fetch_assoc($req)) {
        $result[] = $news['nom_image'];
    }

    mysqli_free_result($req);
    return $result;
}

function get_historique_emprunts($id_objet) {
    $bdd = connecterBase();

    $sql = "SELECT e.date_emprunt, e.date_retour, m.nom
            FROM S2_PROJET_FINAL_emprunts e
            JOIN S2_PROJET_FINAL_membres m ON e.id_membre = m.id_membre
            WHERE e.id_objet = %d";
    
    $sql = sprintf($sql, (int)$id_objet);
    $req = mysqli_query($bdd, $sql);
    $result = array();

    while ($news = mysqli_fetch_assoc($req)) {
        $result[] = $news;
    }

    mysqli_free_result($req);
    return $result;
}

function get_membre($id_membre) {
    $bdd = connecterBase();

    $sql = "SELECT nom, date_naissance, genre, email, ville, image_profil
            FROM S2_PROJET_FINAL_membres
            WHERE id_membre = %d";
    
    $sql = sprintf($sql, (int)$id_membre);
    $req = mysqli_query($bdd, $sql);
    $result = mysqli_fetch_assoc($req);
    mysqli_free_result($req);
    return $result;
}

function get_objets_membre($id_membre) {
    $bdd = connecterBase();

    $sql = "SELECT o.id_objet, o.nom_objet, c.nom_categorie, c.id_categorie
            FROM S2_PROJET_FINAL_objets o
            JOIN S2_PROJET_FINAL_categories_objets c ON o.id_categorie = c.id_categorie
            WHERE o.id_membre = %d
            ORDER BY c.nom_categorie";
    
    $sql = sprintf($sql, (int)$id_membre);
    $req = mysqli_query($bdd, $sql);
    $result = array();

    while ($news = mysqli_fetch_assoc($req)) {
        $result[] = $news;
    }

    mysqli_free_result($req);
    return $result;
}

function ajouter_objet($nom_objet, $id_categorie, $id_membre, $images) {
    $bdd = connecterBase();

    $sql = "INSERT INTO S2_PROJET_FINAL_objets(nom_objet, id_categorie, id_membre) 
            VALUES('%s', %d, %d)";
    $sql = sprintf($sql, 
        mysqli_real_escape_string($bdd, $nom_objet),
        (int)$id_categorie,
        (int)$id_membre
    );
    $req = mysqli_query($bdd, $sql);
    $id_objet = mysqli_insert_id($bdd);

    foreach ($images as $image) {
        $sql_image = "INSERT INTO S2_PROJET_FINAL_objets_images(id_objet, nom_image) 
                      VALUES(%d, '%s')";
        $sql_image = sprintf($sql_image, 
            $id_objet, 
            mysqli_real_escape_string($bdd, $image)
        );
        mysqli_query($bdd, $sql_image);
    }

    return $req;
}

function supprimer_image($id_image) {
    $bdd = connecterBase();

    $sql = "DELETE FROM S2_PROJET_FINAL_objets_images WHERE id_image = %d";
    $sql = sprintf($sql, (int)$id_image);
    $req = mysqli_query($bdd, $sql);
    return $req;
}
?>