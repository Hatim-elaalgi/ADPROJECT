document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize DataTable
    const userTable = new DataTable('#userTable', {
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
        },
        pageLength: 10,
        order: [[0, 'desc']],
        responsive: true,
        dom: '<"top"Bf>rt<"bottom"lip><"clear">',
        buttons: [
            {
                extend: 'excel',
                className: 'btn btn-success btn-sm me-2',
                text: '<i class="fas fa-file-excel me-1"></i> Excel'
            },
            {
                extend: 'pdf',
                className: 'btn btn-danger btn-sm me-2',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF'
            },
            {
                extend: 'print',
                className: 'btn btn-info btn-sm',
                text: '<i class="fas fa-print me-1"></i> Imprimer'
            }
        ]
    });

    // Search functionality
    document.querySelector('#searchInput').addEventListener('keyup', function() {
        userTable.search(this.value).draw();
    });

    // Form validation and submission
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Traitement...';
            }
            form.classList.add('was-validated');
        });
    });

    // Password strength meter
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        input.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            const meter = this.nextElementSibling;
            updatePasswordStrengthMeter(meter, strength);
        });
    });

    // Delete confirmation with SweetAlert2
    document.querySelectorAll('.delete-user').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: "Cette action est irréversible!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Role change confirmation
    document.querySelectorAll('select[name="role"]').forEach(select => {
        select.addEventListener('change', function() {
            const newRole = this.value;
            let confirmationConfig = {
                title: 'Attention!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3498db',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Continuer',
                cancelButtonText: 'Annuler'
            };

            switch(newRole) {
                case 'admin':
                    confirmationConfig = {
                        ...confirmationConfig,
                        title: 'Attribution des droits administrateur',
                        text: "Vous êtes sur le point d'attribuer des droits d'administrateur. Cet utilisateur aura un accès complet à toutes les fonctionnalités.",
                        confirmButtonColor: '#e74c3c',
                        customClass: {
                            popup: 'role-change-admin'
                        }
                    };
                    break;
                case 'responsable_theme':
                    confirmationConfig = {
                        ...confirmationConfig,
                        title: 'Attribution des droits de responsable thème',
                        text: "Vous êtes sur le point d'attribuer des droits de responsable thème. Cet utilisateur pourra gérer les thèmes et leurs contenus.",
                        confirmButtonColor: '#f1c40f',
                        customClass: {
                            popup: 'role-change-responsable'
                        }
                    };
                    break;
                default:
                    return; // No confirmation needed for subscriber role
            }

            Swal.fire(confirmationConfig).then((result) => {
                if (!result.isConfirmed) {
                    this.value = 'subscriber'; // Reset to subscriber if cancelled
                }
            });
        });
    });

    // Animation for success messages
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        successAlert.style.animation = 'slideInDown 0.5s ease-out';
        setTimeout(() => {
            successAlert.style.animation = 'slideOutUp 0.5s ease-out forwards';
        }, 3000);
    }
});

// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    return strength;
}

// Update password strength meter
function updatePasswordStrengthMeter(meter, strength) {
    const colors = ['#e74c3c', '#e67e22', '#f1c40f', '#2ecc71', '#27ae60'];
    const labels = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
    
    meter.style.width = `${(strength / 5) * 100}%`;
    meter.style.backgroundColor = colors[strength - 1];
    meter.textContent = labels[strength - 1];
}

// Show loading state
function showLoading(element) {
    element.disabled = true;
    element.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Chargement...';
}

// Reset loading state
function resetLoading(element, originalText) {
    element.disabled = false;
    element.innerHTML = originalText;
}
