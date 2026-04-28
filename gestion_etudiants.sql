-- ============================================
-- Script SQL : Gestion des Étudiants
-- ============================================

CREATE DATABASE IF NOT EXISTS gestion_etudiants
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE gestion_etudiants;

-- Table des filières
CREATE TABLE IF NOT EXISTS filieres (
  id  INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des étudiants
CREATE TABLE IF NOT EXISTS etudiants (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nom        VARCHAR(100) NOT NULL,
  prenom     VARCHAR(100) NOT NULL,
  filiere_id INT NOT NULL,
  CONSTRAINT fk_filiere
    FOREIGN KEY (filiere_id) REFERENCES filieres(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Données de test : filières
INSERT INTO filieres (nom) VALUES
  ('Informatique'),
  ('Génie Civil'),
  ('Électronique'),
  ('Gestion des Entreprises'),
  ('Réseaux et Télécommunications');
