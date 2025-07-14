<?php
    require("../Inclus/fonctions.php");
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    $login = connecterMembre($email, $password);
    $count = count($login);
    if($count > 0){
        header("location: ../Pages/home.php");
    }
?>