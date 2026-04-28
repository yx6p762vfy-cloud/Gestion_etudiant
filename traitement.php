<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nom       = trim($_POST['nom']      ?? '');
$prenom    = trim($_POST['prenom']   ?? '');
$filiere_id = (int) ($_POST['filiere_id'] ?? 0);

// Validation côté serveur
$errors = [];
if ($nom === '')        $errors[] = 'Le nom est obligatoire.';
if ($prenom === '')     $errors[] = 'Le prénom est obligatoire.';
if ($filiere_id === 0) $errors[] = 'La filière est obligatoire.';

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => implode(' ', $errors)];
    header('Location: index.php');
    exit;
}

// Insertion sécurisée (requête préparée)
try {
    $stmt = $pdo->prepare("INSERT INTO etudiants (nom, prenom, filiere_id) VALUES (:nom, :prenom, :filiere_id)");
    $stmt->execute([
        ':nom'        => $nom,
        ':prenom'     => $prenom,
        ':filiere_id' => $filiere_id,
    ]);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => " Étudiant \"$prenom $nom\" ajouté avec succès."];
} catch (PDOException $e) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Erreur lors de l\'insertion : ' . $e->getMessage()];
}

header('Location: index.php');
exit;
