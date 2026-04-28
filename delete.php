<?php
session_start();
require_once 'config.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id === 0) {
    header('Location: index.php');
    exit;
}

// Vérifier que l'étudiant existe
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = :id");
$stmt->execute([':id' => $id]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Étudiant introuvable.'];
    header('Location: index.php');
    exit;
}

// Supprimer
try {
    $del = $pdo->prepare("DELETE FROM etudiants WHERE id = :id");
    $del->execute([':id' => $id]);
    $_SESSION['flash'] = [
        'type' => 'success',
        'msg'  => " Étudiant \"{$etudiant['prenom']} {$etudiant['nom']}\" supprimé avec succès."
    ];
} catch (PDOException $e) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Erreur lors de la suppression : ' . $e->getMessage()];
}

header('Location: index.php');
exit;
