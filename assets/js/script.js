/**
 * Validation des formulaires étudiants
 */

function validateStudentForm(formId) {
  const form = document.getElementById(formId);
  if (!form) return;

  form.addEventListener('submit', function (e) {
    let valid = true;

    // Réinitialiser erreurs
    form.querySelectorAll('.form-group').forEach(g => g.classList.remove('has-error'));

    // Valider nom
    const nom = form.querySelector('[name="nom"]');
    if (nom && nom.value.trim() === '') {
      showError(nom, 'Le nom est obligatoire.');
      valid = false;
    }

    // Valider prénom
    const prenom = form.querySelector('[name="prenom"]');
    if (prenom && prenom.value.trim() === '') {
      showError(prenom, 'Le prénom est obligatoire.');
      valid = false;
    }

    // Valider filière
    const filiere = form.querySelector('[name="filiere_id"]');
    if (filiere && filiere.value === '') {
      showError(filiere, 'Veuillez sélectionner une filière.');
      valid = false;
    }

    if (!valid) e.preventDefault();
  });
}

function showError(input, message) {
  const group = input.closest('.form-group');
  if (!group) return;
  group.classList.add('has-error');
  const msg = group.querySelector('.error-msg');
  if (msg) msg.textContent = message;
}

// Confirmation suppression
function confirmDelete(id, nom, prenom) {
  return confirm(`Supprimer l'étudiant "${prenom} ${nom}" ?\nCette action est irréversible.`);
}

// Init au chargement
document.addEventListener('DOMContentLoaded', function () {
  validateStudentForm('form-add');
  validateStudentForm('form-edit');
});
