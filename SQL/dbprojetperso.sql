
CREATE DATABASE IF NOT EXISTS dbprojetperso;
-- Créer une base de données appelée dbprojetperso si elle n'existe pas déjà.

USE dbprojetperso;
-- Sélectionne la base de données dbprojetperso pour être utilisée dans les requêtes suivantes.

-- Crée une table appelée categorie si elle n'existe pas déjà, avec les colonnes et les contraintes spécifiées.
CREATE TABLE IF NOT EXISTS categorie
(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
-- id : nom de la colonne
-- INT : type de données, entier
-- UNSIGNED : valeur non signée (ne peut pas être négative)
-- AUTO_INCREMENT : la colonne s'auto-incrémente avec chaque nouvelle entrée
-- PRIMARY KEY : désigne cette colonne comme clé primaire de la table
    nom TEXT NOT NULL,
-- nom : nom de la colonne
-- TEXT : type de données pour stocker des chaînes de texte plus longues
-- NOT NULL : cette colonne ne peut pas être nulle (doit avoir une valeur)
    created_at DATETIME,
-- created_at : nom de la colonne
-- DATETIME : type de données pour stocker la date et l'heure de création
    updated_at DATETIME,
-- updated_at : nom de la colonne
-- DATETIME : type de données pour stocker la date et l'heure de la dernière mise à jour
    active BOOLEAN
-- active : nom de la colonne
-- BOOLEAN : type de données pour stocker des valeurs booléennes (vrai ou faux)
);

CREATE TABLE IF NOT EXISTS client
(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    telephone TEXT,
    mail TEXT NOT NULL,
    password TEXT NOT NULL,
    created_at DATETIME,
    updated_at DATETIME,
    active BOOLEAN,
    INDEX ix_nom (nom),
    INDEX ix_mail (mail)
);

CREATE TABLE IF NOT EXISTS produit
(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    categorie_id INT unsigned,
    nom TEXT,
    description TEXT,
    prix INT,
    stock INT,
    photo TEXT,
    created_at DATETIME,
    updated_at DATETIME,
    active BOOLEAN,
    CONSTRAINT fk_produit_categorie FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON UPDATE CASCADE ON DELETE SET NULL
-- Cette ligne définit une contrainte de clé étrangère (fk_produit_categorie) sur la colonne categorie_id dans la table courante.
-- Cette clé étrangère fait référence à la colonne id de la table categorie.
-- Si la clé primaire (id) de la table categorie est mise à jour, la mise à jour est propagée (CASCADE) à cette table.
-- Si la clé primaire est supprimée, la clé étrangère dans cette table est définie à NULL.
-- Cela garantit l'intégrité des données lors des mises à jour ou des suppressions dans les tables liées.
);
