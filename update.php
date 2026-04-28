<?php
session_start();
require_once 'config.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id === 0) {
    header('Location: index.php');
    exit;
}

// Récupérer l'étudiant
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = :id");
$stmt->execute([':id' => $id]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Étudiant introuvable.'];
    header('Location: index.php');
    exit;
}

// Récupérer les filières
$filieres = $pdo->query("SELECT * FROM filieres ORDER BY nom")->fetchAll();

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom        = trim($_POST['nom']       ?? '');
    $prenom     = trim($_POST['prenom']    ?? '');
    $filiere_id = (int) ($_POST['filiere_id'] ?? 0);

    $errors = [];
    if ($nom === '')        $errors[] = 'Le nom est obligatoire.';
    if ($prenom === '')     $errors[] = 'Le prénom est obligatoire.';
    if ($filiere_id === 0) $errors[] = 'La filière est obligatoire.';

    if (empty($errors)) {
        $upd = $pdo->prepare("UPDATE etudiants SET nom=:nom, prenom=:prenom, filiere_id=:filiere_id WHERE id=:id");
        $upd->execute([
            ':nom'        => $nom,
            ':prenom'     => $prenom,
            ':filiere_id' => $filiere_id,
            ':id'         => $id,
        ]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => " Étudiant \"$prenom $nom\" modifié avec succès."];
        header('Location: index.php');
        exit;
    }

    // Garder les nouvelles valeurs en cas d'erreur
    $etudiant['nom']        = $nom;
    $etudiant['prenom']     = $prenom;
    $etudiant['filiere_id'] = $filiere_id;
    $formErrors = implode(' ', $errors);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier un étudiant — GestEtud</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav>
  
  <div class="nav-links">
    <a href="index.php">Accueil</a>
  </div>
</nav>

<div class="container">

  <div class="page-title">Modifier un étudiant</div>
  <p class="page-subtitle">Mettez à jour les informations</p>

  <?php if (!empty($formErrors)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($formErrors) ?></div>
  <?php endif; ?>

  <div class="card">
    <h2> Formulaire de modification</h2>
    <form id="form-edit" method="POST" action="update.php?id=<?= $id ?>">
      <div class="form-row">
        <div class="form-group">
          <label for="nom">Nom</label>
          <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($etudiant['nom']) ?>">
          <span class="error-msg"></span>
        </div>
        <div class="form-group">
          <label for="prenom">Prénom</label>
          <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($etudiant['prenom']) ?>">
          <span class="error-msg"></span>
        </div>
      </div>
      <div class="form-group">
        <label for="filiere_id">Filière</label>
        <select id="filiere_id" name="filiere_id">
          <option value="">-- Choisir une filière --</option>
          <?php foreach ($filieres as $f): ?>
            <option value="<?= $f['id'] ?>"
              <?= $f['id'] == $etudiant['filiere_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($f['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <span class="error-msg"></span>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary"> Enregistrer</button>
        <a href="index.php" class="btn btn-secondary">Annuler</a>
      </div>
    </form>
  </div>

</div>

<script src="assets/js/script.js"></script>
</body>
</html>
