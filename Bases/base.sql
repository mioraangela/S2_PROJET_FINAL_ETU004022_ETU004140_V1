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

INSERT INTO S2_PROJET_FINAL_membres(nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Alice', '2000-05-12', 'F', 'alice@gmail.com', 'Antananarivo', 'mdp123', 'Alice.jpg'),
('Bob', '1998-09-23', 'M', 'bob@gmail.com', 'Toamasina', 'mdp123', 'Bob.jpg'),
('Claire', '2001-01-18', 'F', 'claire@gmail.com', 'Fianarantsoa', 'mdp123', 'Claire.jpg'),
('David', '1999-07-30', 'M', 'david@gmail.com', 'Mahajanga', 'mdp123', 'David.jpg');

INSERT INTO S2_PROJET_FINAL_categories_objets (nom_categorie) VALUES
('Esthetique'),
('Bricolage'),
('Mecanique'),
('Cuisine');

INSERT INTO S2_PROJET_FINAL_objets (nom_objet, id_categorie, id_membre) VALUES
('Rouge a levres', 1, 1),
('Pinceau', 1, 1),
('Tournevis', 2, 1),
('Clé à molette', 2, 1),
('Couteau de cuisine', 3, 1),
('Poêle à frire', 3, 1),
('Chaise en bois', 4, 1),
('Table en verre', 4, 1),
('Mascara', 1, 1),
('Pince à épiler', 1, 1);

INSERT INTO S2_PROJET_FINAL_objets (nom_objet, id_categorie, id_membre) VALUES
('Crayon de bricolage', 2, 2),
('Scie à bois', 2, 2),
('Clé à cliquet', 2, 2),
('Couteau de chef', 3, 2),
('Mixeur', 3, 2),
('Four à micro-ondes', 3, 2),
('Chaise pliante', 4, 2),
('Table basse', 4, 2),
('Rouge a levres', 1, 2),
('Pinceau', 1, 2);

INSERT INTO S2_PROJET_FINAL_objets (nom_objet, id_categorie, id_membre) VALUES
('Crayon de maquillage', 1, 3),
('Pinceau à maquillage', 1, 3),
('Tournevis cruciforme', 2, 3),
('Clé Allen', 2, 3),
('Couteau à pain', 3, 3),
('Fouet', 3, 3),
('Chaise de bureau', 4, 3),
('Table', 4, 3),
('Mascara waterproof', 1, 3),
('Pince à cheveux', 1, 3);

INSERT INTO S2_PROJET_FINAL_objets (nom_objet, id_categorie, id_membre) VALUES
('Crayon de couleur', 1, 4),
('Pinceau fin', 1, 4),
('Tournevis plat', 2, 4),
('Clé à molette réglable', 2, 4),
('Couteau à éplucher', 3, 4),
('Poêle antiadhésive', 3, 4),
('Chaise en métal', 4, 4),
('Table en bois', 4, 4),
('Rouge a levres mat', 1, 4),
('Pinceau large', 1, 4);

INSERT INTO S2_PROJET_FINAL_emprunts (id_objet, id_membre, date_emprunt, date_retour) VALUES
(22, 4, '2025-07-11', NULL),           
(24, 1, '2025-07-12', '2025-07-14'),   
(26, 2, '2025-07-12', NULL),           
(28, 3, '2025-07-13', '2025-07-15'),  
(30, 2, '2025-07-13', NULL),           
(32, 1, '2025-07-14', NULL),          
(34, 3, '2025-07-14', '2025-07-16'), 
(36, 4, '2025-07-15', NULL), 
(38, 2, '2025-07-15', NULL),       
(40, 1, '2025-07-16', NULL);   

SELECT * 
FROM v_connexion_membre
WHERE email ='%s'
AND mdp ='%s';

SELECT *
FROM S2_PROJET_FINAL_objets;

SELECT *
FROM S2_PROJET_FINAL_emprunts;

SELECT O.id_objet, O.id_categorie, O.id_membre, E.id_emprunt,M.nom, 
O.nom_objet, E.date_emprunt
FROM S2_PROJET_FINAL_objets O
JOIN S2_PROJET_FINAL_membres M
ON M.id_membre = O.id_membre
JOIN S2_PROJET_FINAL_emprunts E
ON E.id_objet = O.id_objet;

SELECT * FROM S2_PROJET_FINAL_objets;

ALTER TABLE S2_PROJET_FINAL_emprunts
ADD COLUMN etat VARCHAR(100) NOT NULL;



