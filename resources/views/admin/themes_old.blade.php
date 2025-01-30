@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/theme-management.css') }}" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-book me-2"></i>Gestion des Thèmes
                    </h3>
                    <div class="d-flex gap-2">
                        <div class="search-box">
                            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un thème...">
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addThemeModal">
                            <i class="fas fa-plus-circle me-2"></i>Ajouter un Thème
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="themeTable">
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
                                        <span class="description-truncate">{{ Str::limit($theme->description, 50) }}</span>
                                        @if(strlen($theme->description) > 50)
                                            <button class="btn btn-link btn-sm show-more" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $theme->id }}">
                                                Voir plus
                                            </button>
                                        @endif
                                    </td>
                                    <td data-label="Responsable">{{ $theme->themeManager->name }}</td>
                                    <td data-label="Statut">
                                        <span class="badge status-{{ $theme->status }}">
                                            {{ ucfirst($theme->status) }}
                                        </span>
                                    </td>
                                    <td data-label="Articles">
                                        <span class="badge bg-info">{{ $theme->articles_count }}</span>
                                    </td>
                                    <td data-label="Abonnés">
                                        <span class="badge bg-success">{{ $theme->subscribers_count }}</span>
                                    </td>
                                    <td data-label="Date de création">{{ $theme->created_at->format('d/m/Y') }}</td>
                                    <td data-label="Actions">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editThemeModal{{ $theme->id }}" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.themes.delete', $theme->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger delete-theme" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.themes.details', $theme->id) }}" class="btn btn-sm btn-success" title="Détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Description Modal -->
                                <div class="modal fade" id="descriptionModal{{ $theme->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Description du thème</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
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
    </div>
</div>

<!-- Add Theme Modal -->
<div class="modal fade" id="addThemeModal" tabindex="-1" aria-labelledby="addThemeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addThemeModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un Thème
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.themes.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                        <div class="invalid-feedback">Veuillez entrer un titre</div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        <div class="invalid-feedback">Veuillez entrer une description</div>
                    </div>
                    <div class="mb-3">
                        <label for="theme_manager_id" class="form-label">Responsable du thème</label>
                        <select class="form-select" id="theme_manager_id" name="theme_manager_id" required>
                            <option value="">Sélectionner un responsable</option>
                            @foreach($themeManagers as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner un responsable</div>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="en_cours">En cours</option>
                            <option value="publie">Publié</option>
                            <option value="refuse">Refusé</option>
                            <option value="retenu">Retenu</option>
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner un statut</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Modifier le Thème
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.themes.update', $theme->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_title{{ $theme->id }}" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="edit_title{{ $theme->id }}" name="title" value="{{ $theme->title }}" required>
                        <div class="invalid-feedback">Veuillez entrer un titre</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description{{ $theme->id }}" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description{{ $theme->id }}" name="description" rows="4" required>{{ $theme->description }}</textarea>
                        <div class="invalid-feedback">Veuillez entrer une description</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_theme_manager_id{{ $theme->id }}" class="form-label">Responsable du thème</label>
                        <select class="form-select" id="edit_theme_manager_id{{ $theme->id }}" name="theme_manager_id" required>
                            @foreach($themeManagers as $manager)
                                <option value="{{ $manager->id }}" {{ $theme->theme_manager_id == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner un responsable</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status{{ $theme->id }}" class="form-label">Statut</label>
                        <select class="form-select" id="edit_status{{ $theme->id }}" name="status" required>
                            <option value="en_cours" {{ $theme->status === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="publie" {{ $theme->status === 'publie' ? 'selected' : '' }}>Publié</option>
                            <option value="refuse" {{ $theme->status === 'refuse' ? 'selected' : '' }}>Refusé</option>
                            <option value="retenu" {{ $theme->status === 'retenu' ? 'selected' : '' }}>Retenu</option>
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner un statut</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
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
@endsection
