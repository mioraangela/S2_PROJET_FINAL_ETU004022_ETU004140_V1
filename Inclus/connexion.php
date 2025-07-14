<?php
    
    function connecterBase()
    {
        static $connect = null;

        if ($connect === null) {
            $connect = mysqli_connect('localhost', 'ETU004140', 'P9gc6PHo', 'db_s2_ETU004140');

            if (!$connect) {
                die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
            }

            mysqli_set_charset($connect, 'utf8mb4');
        }

        return $connect;
    }

    /*function connecterBase()
    {
        static $connect = null;

        if ($connect === null) {
            $connect = mysqli_connect('localhost', 'root', '', 'S2_PROJET_FINAL');

            if (!$connect) {
                die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
            }

            mysqli_set_charset($connect, 'utf8mb4');
        }

        return $connect;
    }*/
?>