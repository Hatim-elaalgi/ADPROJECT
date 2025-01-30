// Theme Management Module
const ThemeManagement = {
    // Configuration
    config: {
        selectors: {
            table: '#themeTable',
            searchInput: '#searchInput',
            deleteButtons: '.delete-theme',
            statusSelects: 'select[name="status"]',
            forms: '.needs-validation',
            successAlert: '.alert-success',
            showMoreButtons: '.show-more',
            filterButtons: '.filter-status',
            exportButtons: '.export-button',
            themeCards: '.theme-card'
        },
        classes: {
            fadeOut: 'fade-out',
            loading: 'is-loading',
            validated: 'was-validated',
            invalid: 'is-invalid',
            hidden: 'd-none'
        },
        dataTable: {
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
                    className: 'btn btn-theme btn-theme--success btn-sm me-2',
                    text: '<i class="fas fa-file-excel me-1"></i> Excel'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-theme btn-theme--danger btn-sm me-2',
                    text: '<i class="fas fa-file-pdf me-1"></i> PDF'
                },
                {
                    extend: 'print',
                    className: 'btn btn-theme btn-theme--info btn-sm',
                    text: '<i class="fas fa-print me-1"></i> Imprimer'
                }
            ]
        }
    },

    // Initialize the module
    init() {
        this.initializeComponents();
        this.attachEventListeners();
        this.setupAnimations();
    },

    // Initialize all components
    initializeComponents() {
        this.initializeDataTable();
        this.initializeTooltips();
        this.initializeFormValidation();
        this.initializeSearchFeature();
    },

    // Initialize DataTable with enhanced features
    initializeDataTable() {
        this.themeTable = new DataTable(this.config.selectors.table, {
            ...this.config.dataTable,
            drawCallback: () => {
                this.setupAnimations();
            }
        });
    },

    // Initialize Bootstrap tooltips
    initializeTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        this.tooltips = tooltipTriggerList.map(tooltipTriggerEl => 
            new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover'
            })
        );
    },

    // Attach all event listeners
    attachEventListeners() {
        this.attachDeleteListeners();
        this.attachStatusChangeListeners();
        this.attachFormValidationListeners();
        this.attachFilterListeners();
        this.attachExportListeners();
        this.attachCardInteractionListeners();
        this.attachShowMoreListeners();
    },

    // Handle theme deletion
    attachDeleteListeners() {
        document.querySelectorAll(this.config.selectors.deleteButtons).forEach(button => {
            button.addEventListener('click', (e) => this.handleDelete(e));
        });
    },

    async handleDelete(e) {
        e.preventDefault();
        const form = e.target.closest('form');
        
        const result = await Swal.fire({
            title: 'Êtes-vous sûr?',
            html: `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Cette action supprimera également tous les articles associés à ce thème.
                    <br>
                    <strong>Cette action est irréversible!</strong>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-trash me-2"></i>Oui, supprimer',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
            customClass: {
                popup: 'theme-modal',
                confirmButton: 'btn-theme btn-theme--danger',
                cancelButton: 'btn-theme btn-theme--secondary'
            }
        });

        if (result.isConfirmed) {
            this.showLoadingState(form);
            form.submit();
        }
    },

    // Handle status changes
    attachStatusChangeListeners() {
        document.querySelectorAll(this.config.selectors.statusSelects).forEach(select => {
            select.addEventListener('change', (e) => this.handleStatusChange(e));
        });
    },

    async handleStatusChange(e) {
        const select = e.target;
        const originalValue = select.dataset.originalValue;
        const newStatus = select.value;
        
        const confirmationConfig = this.getStatusConfirmationConfig(newStatus);
        
        const result = await Swal.fire(confirmationConfig);
        
        if (!result.isConfirmed) {
            select.value = originalValue;
        } else {
            select.dataset.originalValue = newStatus;
            this.updateThemeStatus(select);
        }
    },

    getStatusConfirmationConfig(status) {
        const configs = {
            refuse: {
                title: 'Refuser le thème?',
                text: "Cette action notifiera le responsable du thème. Êtes-vous sûr?",
                confirmButtonColor: '#ef4444'
            },
            publie: {
                title: 'Publier le thème?',
                text: "Le thème sera visible par tous les utilisateurs. Continuer?",
                confirmButtonColor: '#22c55e'
            },
            default: {
                title: 'Changer le statut?',
                text: "Vous êtes sur le point de changer le statut du thème.",
                confirmButtonColor: '#4f46e5'
            }
        };

        return {
            ...(configs[status] || configs.default),
            icon: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Oui, changer',
            cancelButtonText: 'Annuler',
            customClass: {
                popup: 'theme-modal'
            }
        };
    },

    // Form validation
    attachFormValidationListeners() {
        document.querySelectorAll(this.config.selectors.forms).forEach(form => {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        });
    },

    handleFormSubmit(e) {
        const form = e.target;
        
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            this.highlightInvalidFields(form);
        } else {
            this.showLoadingState(form);
        }
        
        form.classList.add(this.config.classes.validated);
    },

    highlightInvalidFields(form) {
        form.querySelectorAll(':invalid').forEach(field => {
            field.classList.add(this.config.classes.invalid);
            this.showFieldError(field);
        });
    },

    showFieldError(field) {
        const errorMessage = field.dataset.errorMessage || 'Ce champ est requis';
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback animate-slide-in';
        errorDiv.textContent = errorMessage;
        field.parentNode.appendChild(errorDiv);
    },

    // Loading states
    showLoadingState(element) {
        const submitBtn = element.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Traitement...
            `;
        }
    },

    // Setup animations
    setupAnimations() {
        this.setupRowAnimations();
        this.setupCardAnimations();
        this.setupAlertAnimations();
    },

    setupRowAnimations() {
        const rows = document.querySelectorAll(`${this.config.selectors.table} tbody tr`);
        rows.forEach((row, index) => {
            row.style.animation = `slideIn ${0.3 + index * 0.1}s ease-out`;
        });
    },

    setupCardAnimations() {
        const cards = document.querySelectorAll(this.config.selectors.themeCards);
        cards.forEach((card, index) => {
            card.style.animation = `slideIn ${0.3 + index * 0.1}s ease-out`;
        });
    },

    setupAlertAnimations() {
        const alert = document.querySelector(this.config.selectors.successAlert);
        if (alert) {
            alert.classList.add('animate-slide-in');
            setTimeout(() => {
                alert.classList.add(this.config.classes.fadeOut);
            }, 3000);
        }
    },

    // Filter functionality
    attachFilterListeners() {
        document.querySelectorAll(this.config.selectors.filterButtons).forEach(button => {
            button.addEventListener('click', (e) => this.handleFilter(e));
        });
    },

    handleFilter(e) {
        const status = e.target.dataset.status;
        this.themeTable.column(4).search(status).draw();
    },

    // Export functionality
    attachExportListeners() {
        document.querySelectorAll(this.config.selectors.exportButtons).forEach(button => {
            button.addEventListener('click', (e) => this.handleExport(e));
        });
    },

    handleExport(e) {
        const format = e.target.dataset.format;
        this.themeTable.button(`.buttons-${format}`).trigger();
    },

    // Card interactions
    attachCardInteractionListeners() {
        document.querySelectorAll(this.config.selectors.themeCards).forEach(card => {
            card.addEventListener('mouseenter', (e) => this.handleCardHover(e));
            card.addEventListener('mouseleave', (e) => this.handleCardLeave(e));
        });
    },

    handleCardHover(e) {
        const card = e.target;
        card.style.transform = 'translateY(-5px)';
        card.style.boxShadow = 'var(--shadow-lg)';
    },

    handleCardLeave(e) {
        const card = e.target;
        card.style.transform = '';
        card.style.boxShadow = '';
    },

    // Show more functionality
    attachShowMoreListeners() {
        document.querySelectorAll(this.config.selectors.showMoreButtons).forEach(button => {
            button.addEventListener('click', (e) => this.handleShowMore(e));
        });
    },

    handleShowMore(e) {
        const description = e.target.closest('td').querySelector('.description-truncate').getAttribute('data-full-text');
        const modal = bootstrap.Modal.getOrCreateInstance(document.querySelector(e.target.getAttribute('data-bs-target')));
        modal.show();
    },

    // Search functionality
    initializeSearchFeature() {
        document.querySelector(this.config.selectors.searchInput).addEventListener('keyup', () => {
            this.themeTable.search(this.config.selectors.searchInput.value).draw();
        });
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    ThemeManagement.init();
});
