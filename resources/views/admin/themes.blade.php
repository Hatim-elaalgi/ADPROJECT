@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/theme-management.css') }}" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success animate-slide-in" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    
    <div class="theme-card mb-4">
        <div class="theme-card__header">
            <div class="d-flex justify-content-between align-items-center">
                <h3>
                    <i class="fas fa-book me-2"></i>Gestion des Thèmes
                </h3>
                <div class="d-flex gap-3">
                    <div class="search-box">
                        <i class="fas fa-search search-box__icon"></i>
                        <input type="text" id="searchInput" class="search-box__input" placeholder="Rechercher un thème...">
                    </div>
                    <button type="button" class="btn-theme btn-theme--primary" data-bs-toggle="modal" data-bs-target="#addThemeModal">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter un Thème
                    </button>
                </div>
            </div>
        </div>

        <div class="theme-card__stats">
            <div class="stat-item">
                <div class="stat-item__value">{{ $themes->count() }}</div>
                <div class="stat-item__label">Total des thèmes</div>
            </div>
            <div class="stat-item">
                <div class="stat-item__value">{{ $themes->where('status', 'publie')->count() }}</div>
                <div class="stat-item__label">Thèmes publiés</div>
            </div>
            <div class="stat-item">
                <div class="stat-item__value">{{ $themes->where('status', 'en_cours')->count() }}</div>
                <div class="stat-item__label">En cours</div>
            </div>
            <div class="stat-item">
                <div class="stat-item__value">{{ $themes->where('status', 'retenu')->count() }}</div>
                <div class="stat-item__label">Thèmes retenus</div>
            </div>
        </div>

        <div class="p-4">
            <div class="mb-3 d-flex gap-2">
                <button class="btn-theme btn-theme--secondary filter-status" data-status="">
                    <i class="fas fa-list me-1"></i>Tous
                </button>
                <button class="btn-theme btn-theme--success filter-status" data-status="publie">
                    <i class="fas fa-check-circle me-1"></i>Publiés
                </button>
                <button class="btn-theme btn-theme--warning filter-status" data-status="en_cours">
                    <i class="fas fa-clock me-1"></i>En cours
                </button>
                <button class="btn-theme btn-theme--info filter-status" data-status="retenu">
                    <i class="fas fa-star me-1"></i>Retenus
                </button>
                <button class="btn-theme btn-theme--danger filter-status" data-status="refuse">
                    <i class="fas fa-times-circle me-1"></i>Refusés
                </button>
            </div>

            <div class="table-responsive">
                <table class="theme-table" id="themeTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Responsable</th>
                            <th>Statut</th>
                            <th>Articles</th>
                            <th>Abonnés</th>
                            <th>Date de création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($themes as $theme)
                        <tr>
                            <td data-label="ID">{{ $theme->id }}</td>
                            <td data-label="Titre">{{ $theme->title }}</td>
                            <td data-label="Description">
                                <span class="description-truncate" data-full-text="{{ $theme->description }}">
                                    {{ Str::limit($theme->description, 50) }}
                                </span>
                                @if(strlen($theme->description) > 50)
                                    <button class="btn-theme btn-theme--secondary btn-sm show-more" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $theme->id }}">
                                        <i class="fas fa-eye me-1"></i>Voir plus
                                    </button>
                                @endif
                            </td>
                            <td data-label="Responsable">{{ $theme->themeManager->name }}</td>
                            <td data-label="Statut">
                                <select class="form-select status-select" name="status" data-theme-id="{{ $theme->id }}" data-url="{{ route('admin.themes.update', $theme->id) }}">
                                    <option value="en_cours" {{ $theme->status === 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="publie" {{ $theme->status === 'publie' ? 'selected' : '' }}>Publié</option>
                                    <option value="retenu" {{ $theme->status === 'retenu' ? 'selected' : '' }}>Retenu</option>
                                    <option value="refuse" {{ $theme->status === 'refuse' ? 'selected' : '' }}>Refusé</option>
                                </select>
                            </td>
                            <td data-label="Articles">
                                <span class="badge bg-info">{{ $theme->articles_count }}</span>
                            </td>
                            <td data-label="Abonnés">
                                <span class="badge bg-success">{{ $theme->subscribers_count }}</span>
                            </td>
                            <td data-label="Date de création">{{ $theme->created_at->format('d/m/Y') }}</td>
                            <td data-label="Actions">
                                <div class="d-flex gap-2">
                                    <button class="btn-theme btn-theme--info btn-sm" data-bs-toggle="modal" data-bs-target="#editThemeModal{{ $theme->id }}" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.themes.delete', $theme->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-theme btn-theme--danger btn-sm delete-theme" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.themes.details', $theme->id) }}" class="btn-theme btn-theme--success btn-sm" title="Détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Description Modal -->
                        <div class="modal fade" id="descriptionModal{{ $theme->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="theme-modal">
                                    <div class="theme-modal__header">
                                        <h5 class="modal-title">Description du thème</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="theme-modal__body">
                                        {{ $theme->description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Theme Modal -->
<div class="modal fade" id="addThemeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="theme-modal">
            <div class="theme-modal__header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un Thème
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.themes.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="theme-modal__body">
                    <div class="theme-form__group">
                        <label class="theme-form__label" for="title">Titre</label>
                        <input type="text" class="theme-form__input" id="title" name="title" required data-error-message="Le titre est requis">
                    </div>
                    <div class="theme-form__group">
                        <label class="theme-form__label" for="description">Description</label>
                        <textarea class="theme-form__input" id="description" name="description" rows="4" required data-error-message="La description est requise"></textarea>
                    </div>
                    <div class="theme-form__group">
                        <label class="theme-form__label" for="theme_manager_id">Responsable du thème</label>
                        <select class="theme-form__input" id="theme_manager_id" name="theme_manager_id" required data-error-message="Veuillez sélectionner un responsable">
                            <option value="">Sélectionner un responsable</option>
                            @foreach($themeManagers as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="theme-form__group">
                        <label class="theme-form__label" for="status">Statut</label>
                        <select class="theme-form__input" id="status" name="status" required data-error-message="Veuillez sélectionner un statut">
                            <option value="en_cours">En cours</option>
                            <option value="publie">Publié</option>
                            <option value="refuse">Refusé</option>
                            <option value="retenu">Retenu</option>
                        </select>
                    </div>
                </div>
                <div class="theme-modal__footer">
                    <button type="button" class="btn-theme btn-theme--secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn-theme btn-theme--primary">
                        <i class="fas fa-save me-2"></i>Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($themes as $theme)
<!-- Edit Theme Modal -->
<div class="modal fade" id="editThemeModal{{ $theme->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="theme-modal">
            <div class="theme-modal__header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Modifier le Thème
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.themes.update', $theme->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                <div class="theme-modal__body">
                    <div class="theme-form__group">
                        <label class="theme-form__label" for="edit_title{{ $theme->id }}">Titre</label>
                        <input type="text" class="theme-form__input" id="edit_title{{ $theme->id }}" name="title" value="{{ $theme->title }}" required data-error-message="Le titre est requis">
                    </div>
                    <div class="theme-form__group">
                        <label class="theme-form__label" for="edit_description{{ $theme->id }}">Description</label>
                        <textarea class="theme-form__input" id="edit_description{{ $theme->id }}" name="description" rows="4" required data-error-message="La description est requise">{{ $theme->description }}</textarea>
                    </div>
                    <div class="theme-form__group">
                        <label class="theme-form__label" for="edit_theme_manager_id{{ $theme->id }}">Responsable du thème</label>
                        <select class="theme-form__input" id="edit_theme_manager_id{{ $theme->id }}" name="theme_manager_id" required data-error-message="Veuillez sélectionner un responsable">
                            @foreach($themeManagers as $manager)
                                <option value="{{ $manager->id }}" {{ $theme->user_id == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="theme-form__group">
                        <label class="theme-form__label" for="edit_status{{ $theme->id }}">Statut</label>
                        <select class="theme-form__input" id="edit_status{{ $theme->id }}" name="status" required data-original-value="{{ $theme->status }}" data-error-message="Veuillez sélectionner un statut">
                            <option value="en_cours" {{ $theme->status === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="publie" {{ $theme->status === 'publie' ? 'selected' : '' }}>Publié</option>
                            <option value="refuse" {{ $theme->status === 'refuse' ? 'selected' : '' }}>Refusé</option>
                            <option value="retenu" {{ $theme->status === 'retenu' ? 'selected' : '' }}>Retenu</option>
                        </select>
                    </div>
                </div>
                <div class="theme-modal__footer">
                    <button type="button" class="btn-theme btn-theme--secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn-theme btn-theme--primary">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/theme-management.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gérer le changement de statut
    const statusSelects = document.querySelectorAll('.status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', async function() {
            const url = this.dataset.url;
            const status = this.value;
            const token = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                });

                const data = await response.json();

                if (data.success) {
                    // Afficher un message de succès
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    // Rafraîchir la page après un court délai
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Erreur lors de la mise à jour');
                }
            } catch (error) {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: error.message || 'Une erreur est survenue lors de la mise à jour du statut'
                });
                // Restaurer la valeur précédente
                this.value = this.querySelector('option[selected]').value;
            }
        });
    });
});
</script>
@endsection
