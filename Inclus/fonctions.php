<?php
    require("connexion.php");

    function connecterMembre($email, $password) {
        $bdd = connecterBase();

        $sql = "SELECT * 
        FROM v_connexion_membre
        WHERE email ='%s'
        AND mdp ='%s';";

        $sql = sprintf($sql, $email, $password);
        $req = mysqli_query($bdd,$sql);
        $result = array();

        while ($news = mysqli_fetch_assoc($req)) {
            $result[] = $news;
        }

        mysqli_free_result($req);

        return $result;
    }
?>