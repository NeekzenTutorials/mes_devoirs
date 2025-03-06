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

CREATE TABLE Realise(
   id_utilisateur INT,
   id_exercice INT,
   reponse VARCHAR(50),
   duree DATETIME,
   PRIMARY KEY(id_utilisateur, id_exercice),
   FOREIGN KEY(id_utilisateur) REFERENCES Enfants(id_utilisateur),
   FOREIGN KEY(id_exercice) REFERENCES Exercices(id_exercice)
);
