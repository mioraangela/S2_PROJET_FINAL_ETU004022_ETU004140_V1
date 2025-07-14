<?php
    require("connexion.php");

    function connecterMembre($email, $password) {
        $bdd = connecterBase();

        $sql = "SELECT * 
        FROM v_connexion_membre
        WHERE email ='%s'
        AND mdp ='%s';";

        $sql = sprintf($sql, $email, $password);
        echo $sql;
        $req = mysqli_query($bdd,$sql);
        $result = array();

        while ($news = mysqli_fetch_assoc($req)) {
            $result[] = $news;
        }

        mysqli_free_result($req);

        return $result;
    }
    function setNewMembre($nom, $date_naissance, $genre, $email, $ville, $mdp, $photo){
        $bdd = connecterBase();

        $sql = "INSERT INTO S2_PROJET_FINAL_membres(nom, date_naissance, genre, email, ville, mdp, image_profil) 
        VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s');";

        $sql = sprintf($sql, $nom, $date_naissance, $genre, $email, $ville, $mdp, $photo);
        echo $sql;
        $req = mysqli_query($bdd,$sql);
    }
    
?>