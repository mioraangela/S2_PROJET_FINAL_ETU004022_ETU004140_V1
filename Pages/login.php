<!DOCTYPE html>
<html>
    <head>
        <title>
            Connexion
        </title>

        <link rel="stylesheet" href="../Styles/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="../Styles/connexion.css">
        <script src="../Styles/bootstrap/js/bootstrap.bundle.min.js"></script>
    </head>

    <body>
        <header>

        </header>

        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <h1 class="text-center">
                            Connexion
                        </h1>
                        <p class="text-center">
                            Veuillez entrer vos identifiants pour vous connecter.
                        </p>
                        <form action="../Traitements/traitementLogin.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                        </form>
                        <p class="text-center mt-3">
                            Pas encore inscrit ? <a href="inscription.php">Inscrivez-vous ici</a>.
                        </p>
                    </div>
                </div>
            </div>
        </main>

        <footer>
            <div class="container text-center">
                <p>&copy; 004022-004140. Tous droits réservés.</p>
            </div>
        </footer>
    </body>
</html>