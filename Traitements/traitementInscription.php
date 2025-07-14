<?php 
    require("../Inclus/fonctions.php");
    $nom = $_POST['username'];
    $date_naissance = $_POST['dob'];
    $genre = $_POST['gender'];
    $email = $_POST['email'];
    $ville = $_POST['city'];
    $password = $_POST['password'];
    

    if($nom !="" && $date_naissance!=""&& $genre!="" &&$email!=""&&$ville!=""&&$password!=""){
        $photo = $nom."jpg";
        setNewMembre($nom, $date_naissance, $genre, $email, $ville, $mdp, $photo);
        header("location: ../Pages/login.php");
    }
    else{
        header("location: ../Pages/inscription.php");
    }
    


?>