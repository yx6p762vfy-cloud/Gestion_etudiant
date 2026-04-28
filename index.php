<?php
require_once 'config.php';

// Récupérer les filières pour le formulaire
$filieres = $pdo->query("SELECT * FROM filieres ORDER BY nom")->fetchAll();

// Récupérer tous les étudiants avec leur filière (jointure)
$stmt = $pdo->query("
    SELECT e.id, e.nom, e.prenom, f.nom AS filiere_nom
    FROM etudiants e
    LEFT JOIN filieres f ON e.filiere_id = f.id
    ORDER BY e.nom, e.prenom
");
$etudiants = $stmt->fetchAll();

$totalEtudiants = count($etudiants);
$totalFilieres  = count($filieres);

// Message flash (après ajout / modif / suppression)
$flash = $_SESSION['flash'] ?? null;
if (isset($_SESSION['flash'])) {
    session_start();
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Étudiants</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav>
  
  <div class="nav-links">
    <a href="index.php" class="active">Accueil</a>
  </div>
</nav>

<div class="container">

  <div class="page-title">Étudiants</div>
  <p class="page-subtitle">Gérez les étudiants et leurs filières</p>

  <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['msg']) ?></div>
  <?php endif; ?>

  <!-- STATS -->
  <div class="stats-bar">
    <div class="stat-item">
      <div class="val"><?= $totalEtudiants ?></div>
      <div class="lbl">Étudiants inscrits</div>
    </div>
    <div class="stat-item">
      <div class="val"><?= $totalFilieres ?></div>
      <div class="lbl">Filières disponibles</div>
    </div>
  </div>

  <!-- FORMULAIRE D'AJOUT -->
  <div class="card">
    <h2> Ajouter un étudiant</h2>
    <form id="form-add" method="POST" action="traitement.php">
      <div class="form-row">
        <div class="form-group">
          <label for="nom">Nom</label>
          <input type="text" id="nom" name="nom" placeholder="Ex : Dupont">
          <span class="error-msg"></span>
        </div>
        <div class="form-group">
          <label for="prenom">Prénom</label>
          <input type="text" id="prenom" name="prenom" placeholder="Ex : Jean">
          <span class="error-msg"></span>
        </div>
      </div>
      <div class="form-group">
        <label for="filiere_id">Filière</label>
        <select id="filiere_id" name="filiere_id">
          <option value="">-- Choisir une filière --</option>
          <?php foreach ($filieres as $f): ?>
            <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nom']) ?></option>
          <?php endforeach; ?>
        </select>
        <span class="error-msg"></span>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </form>
  </div>