CREATE TABLE Utilisateurs(
IdUtil int PRIMARY KEY AUTO_INCREMENT,
Profil_Util varchar(255) n,
Nom_Util varchar(100) not null,
Adress_Util varchar(100) not null,
Telephone_Util numeric(20) not null,
Email varchar(60) not null,
Motpass varchar(20) not null,
role_Util ENUM("Utilisateur","Admin") DEFAULT "Utilisateur"

);