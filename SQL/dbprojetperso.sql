
CREATE DATABASE IF NOT EXISTS dbprojetperso;

USE dbprojetperso;

CREATE TABLE IF NOT EXISTS categorie
(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom TEXT NOT NULL,
    created_at DATETIME,
    updated_at DATETIME,
    active BOOLEAN
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
);
