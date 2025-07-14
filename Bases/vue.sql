CREATE OR REPLACE VIEW v_connexion_membre AS
SELECT id_membre, email, mdp
FROM S2_PROJET_FINAL_membres;

CREATE OR REPLACE VIEW v_emprunt AS
SELECT O.id_objet, O.id_categorie, O.id_membre, E.id_emprunt,M.nom, 
O.nom_objet, E.date_emprunt
FROM S2_PROJET_FINAL_objets O
JOIN S2_PROJET_FINAL_membres M
ON M.id_membre = O.id_membre
JOIN S2_PROJET_FINAL_emprunts E
ON E.id_objet = O.id_objet;

