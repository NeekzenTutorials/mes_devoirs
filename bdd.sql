CREATE TABLE Utilisateurs(
   id_utilisateur INT AUTO_INCREMENT,
   nom VARCHAR(50),
   prenom VARCHAR(50),
   mot_de_passe VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_utilisateur)
);

CREATE TABLE Enseignants(
   id_utilisateur INT,
   PRIMARY KEY(id_utilisateur),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateurs(id_utilisateur)
);

CREATE TABLE Parents(
   id_utilisateur INT,
   PRIMARY KEY(id_utilisateur),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateurs(id_utilisateur)
);

CREATE TABLE Classes(
   id_classe INT AUTO_INCREMENT,
   Libelle VARCHAR(10) NOT NULL,
   PRIMARY KEY(id_classe)
);

CREATE TABLE Exercices(
   id_exercice INT AUTO_INCREMENT,
   type VARCHAR(50) NOT NULL,
   enonce VARCHAR(250),
   resultat VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_exercice)
);

CREATE TABLE Enfants(
   id_utilisateur INT,
   id_classe INT,
   PRIMARY KEY(id_utilisateur),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateurs(id_utilisateur),
   FOREIGN KEY(id_classe) REFERENCES Classes(id_classe)
);

CREATE TABLE Parente(
   id_utilisateur INT,
   id_utilisateur_1 INT,
   PRIMARY KEY(id_utilisateur, id_utilisateur_1),
   FOREIGN KEY(id_utilisateur) REFERENCES Enfants(id_utilisateur),
   FOREIGN KEY(id_utilisateur_1) REFERENCES Parents(id_utilisateur)
);

CREATE TABLE Encadre(
   id_utilisateur INT,
   id_utilisateur_1 INT,
   PRIMARY KEY(id_utilisateur, id_utilisateur_1),
   FOREIGN KEY(id_utilisateur) REFERENCES Enseignants(id_utilisateur),
   FOREIGN KEY(id_utilisateur_1) REFERENCES Enfants(id_utilisateur)
);

CREATE TABLE Sessions (
   id_session INT AUTO_INCREMENT,
   id_utilisateur INT NOT NULL,
   date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY(id_session),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateurs(id_utilisateur) ON DELETE CASCADE
);

CREATE TABLE Realise(
   id_utilisateur INT,
   id_exercice INT,
   id_session INT NOT NULL,
   num_question INT NOT NULL,
   reponse VARCHAR(50),
   duree INT,
   valide TINYINT(1),
   PRIMARY KEY(id_utilisateur, id_exercice, id_session, num_question),
   FOREIGN KEY(id_utilisateur) REFERENCES Enfants(id_utilisateur) ON DELETE CASCADE,
   FOREIGN KEY(id_exercice) REFERENCES Exercices(id_exercice) ON DELETE CASCADE,
   FOREIGN KEY(id_session) REFERENCES Sessions(id_session) ON DELETE CASCADE
);
