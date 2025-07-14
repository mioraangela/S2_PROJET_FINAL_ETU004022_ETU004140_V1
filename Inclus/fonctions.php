<?php
require("connexion.php");

function connecterMembre($email, $password) {
    $bdd = connecterBase();
    $sql = "SELECT * FROM v_connexion_membre WHERE email = ? AND mdp = ?";
    $stmt = mysqli_prepare($bdd, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $data;
}

function setNewMembre($nom, $date_naissance, $genre, $email, $ville, $mdp, $photo) {
    $bdd = connecterBase();
    $sql = "INSERT INTO S2_PROJET_FINAL_membres(nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($bdd, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", $nom, $date_naissance, $genre, $email, $ville, $mdp, $photo);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

function get_liste_emprunt() {
    $bdd = connecterBase();
    $sql = "SELECT * FROM v_emprunt";
    $req = mysqli_query($bdd, $sql);
    $result = mysqli_fetch_all($req, MYSQLI_ASSOC);
    mysqli_free_result($req);
    return $result;
}

function get_categories() {
    $bdd = connecterBase();
    $sql = "SELECT * FROM S2_PROJET_FINAL_categories_objets";
    $req = mysqli_query($bdd, $sql);
    $result = mysqli_fetch_all($req, MYSQLI_ASSOC);
    mysqli_free_result($req);
    return $result;
}

function get_liste_objets($categorie = null, $nom = null, $disponible = false) {
    $bdd = connecterBase();
    $sql = "SELECT o.id_objet, o.nom_objet, o.id_categorie, o.id_membre, c.nom_categorie, m.nom as nom_membre,
            (SELECT nom_image FROM S2_PROJET_FINAL_objets_images i WHERE i.id_objet = o.id_objet ORDER BY i.id_image LIMIT 1) as nom_image,
            (SELECT e.date_retour FROM S2_PROJET_FINAL_emprunts e WHERE e.id_objet = o.id_objet AND e.date_retour IS NULL LIMIT 1) as emprunt_actif,
            (SELECT e.date_retour FROM S2_PROJET_FINAL_emprunts e WHERE e.id_objet = o.id_objet AND e.date_retour IS NOT NULL ORDER BY e.date_retour DESC LIMIT 1) as date_retour
            FROM S2_PROJET_FINAL_objets o
            JOIN S2_PROJET_FINAL_categories_objets c ON o.id_categorie = c.id_categorie
            JOIN S2_PROJET_FINAL_membres m ON o.id_membre = m.id_membre
            WHERE 1=1";
    
    $params = [];
    $types = "";
    if ($categorie) {
        $sql .= " AND o.id_categorie = ?";
        $params[] = $categorie;
        $types .= "i";
    }
    if ($nom) {
        $sql .= " AND o.nom_objet LIKE ?";
        $params[] = "%$nom%";
        $types .= "s";
    }
    if ($disponible) {
        $sql .= " AND NOT EXISTS (SELECT 1 FROM S2_PROJET_FINAL_emprunts e WHERE e.id_objet = o.id_objet AND e.date_retour IS NULL)";
    }

    $stmt = mysqli_prepare($bdd, $sql);
    if ($params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $data;
}
}

function ajouterObjet($nom_objet, $id_categorie, $id_membre, $images) {
    $bdd = connecterBase();
    
    $sql = "INSERT INTO S2_PROJET_FINAL_objets (nom_objet, id_categorie, id_membre) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($bdd, $sql);
    mysqli_stmt_bind_param($stmt, "sii", $nom_objet, $id_categorie, $id_membre);
    mysqli_stmt_execute($stmt);
    $id_objet = mysqli_insert_id($bdd);
    mysqli_stmt_close($stmt);

    $upload_dir = "Uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    foreach ($images['name'] as $index => $name) {
        if ($images['error'][$index] == UPLOAD_ERR_OK) {
            $tmp_name = $images['tmp_name'][$index];
            $file_name = uniqid() . "_" . basename($name);
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($tmp_name, $file_path)) {
                $sql = "INSERT INTO S2_PROJET_FINAL_objets_images (id_objet, nom_image) VALUES (?, ?)";
                $stmt = mysqli_prepare($bdd, $sql);
                mysqli_stmt_bind_param($stmt, "is", $id_objet, $file_name);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }

    return $id_objet;
}

function supprimerImage($id_objet, $nom_image, $id_membre) {
    $bdd = connecterBase();
    
    $sql = "SELECT id_membre FROM S2_PROJET_FINAL_objets WHERE id_objet = ?";
    $stmt = mysqli_prepare($bdd, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_objet);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $objet = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($objet['id_membre'] != $id_membre) {
        return false;
    }

    $sql = "DELETE FROM S2_PROJET_FINAL_objets_Images WHERE id_objet = ? AND nom_image = ?";
    $stmt = mysqli_prepare($bdd, $sql);
    mysqli_stmt_bind_param($stmt, "is", $id_objet, $nom_image);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        @unlink("Uploads/" . $nom_image);
    }

    return $result;
}

function emprunterObjet($id_objet, $id_membre, $duree) {
    $bdd = connecterBase();
    
    $sql = "SELECT id_objet FROM S2_PROJET_FINAL_objets WHERE id_objet = ? AND id_membre != ? 
            AND NOT EXISTS (SELECT 1 FROM*S2_PROJET_FINAL_emprunts WHERE id_objet = ? AND date_retour IS NULL)";
    $stmt = mysqli_prepare($bdd, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $id_objet, $id_membre, $id_objet);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 0) {
        mysqli_stmt_close($stmt);
        return false;
    }
    mysqli_stmt_close($stmt);

    $date_emprunt = date('Y-m-d');
    $date_retour = date('Y-m-d', strtotime("+$duree days"));
    $sql = "INSERT INTO S2_PROJET_FINAL_emprunts (id_objet, id_membre, date_emprunt, date_retour) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($bdd, $sql);
    mysqli_stmt_bind_param($stmt, "iiss", $id_objet, $id_membre, $date_emprunt, $date_retour);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}
?>