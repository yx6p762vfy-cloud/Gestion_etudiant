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
  <!-- TABLEAU DES ÉTUDIANTS -->
  <div class="card">
    <h2> Liste des étudiants</h2>
    <?php if (empty($etudiants)): ?>
      <div class="empty-state">
        <p>Aucun étudiant enregistré pour l'instant.</p>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Filière</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($etudiants as $i => $e): ?>
            <tr>
              <td style="color:var(--muted)"><?= $i + 1 ?></td>
              <td><strong><?= htmlspecialchars($e['nom']) ?></strong></td>
              <td><?= htmlspecialchars($e['prenom']) ?></td>
              <td><span class="badge"><?= htmlspecialchars($e['filiere_nom'] ?? '—') ?></span></td>
              <td>
                <div class="actions">
                  <a href="update.php?id=<?= $e['id'] ?>" class="btn btn-edit"> Modifier</a>
                  <a href="delete.php?id=<?= $e['id'] ?>"
                     class="btn btn-danger"
                     onclick="return confirmDelete(<?= $e['id'] ?>, '<?= addslashes($e['nom']) ?>', '<?= addslashes($e['prenom']) ?>')">
                     Supprimer
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

</div>

<script src="assets/js/script.js"></script>
</body>
</html>
