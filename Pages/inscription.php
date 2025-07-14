<!DOCTYPE html>
<html>
    <head>
        <title>
            Inscription
        </title>

        <link rel="stylesheet" href="../Styles/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="../Styles/inscription.css">
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
                            Inscription
                        </h1>
                        <p class="text-center">
                            Veuillez remplir le formulaire ci-dessous pour vous inscrire.
                        </p>
                        <form action="../Traitements/traitementInscription.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date de naissance</label>
                                <input type="date" class="form-control" id="dob" name="dob" required>
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Genre</label>
                                <select name="gender" id="gender">
                                    <option value="" disabled selected>Choisissez votre genre</option>
                                    <option value="F">Femme</option>
                                    <option value="M">Homme</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">S'inscrire</button>
                        </form>
                        <p class="text-center mt-3">
                            Déjà inscrit ? <a href="login.php">Connectez-vous ici</a>.
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