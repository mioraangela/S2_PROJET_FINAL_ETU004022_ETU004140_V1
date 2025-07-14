CREATE TABLE S2_PROJET_FINAL_membres(
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    date_naissance DATE,
    genre VARCHAR(10),
    email VARCHAR(100) UNIQUE NOT NULL,
    ville VARCHAR(100),
    mdp VARCHAR(255) NOT NULL,
    image_profil VARCHAR(255)
);

CREATE TABLE S2_PROJET_FINAL_categories_objets(
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100) NOT NULL
);

CREATE TABLE S2_PROJET_FINAL_objets(
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100) NOT NULL,
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY (id_categorie) REFERENCES S2_PROJET_FINAL_categories_objets(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES S2_PROJET_FINAL_membres(id_membre)
);

CREATE TABLE S2_PROJET_FINAL_objets_images(
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_objet) REFERENCES S2_PROJET_FINAL_objets(id_objet)
);

CREATE TABLE S2_PROJET_FINAL_emprunts(
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE NOT NULL,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES S2_PROJET_FINAL_objets(id_objet),
    FOREIGN KEY (id_membre) REFERENCES S2_PROJET_FINAL_membres(id_membre)
);

