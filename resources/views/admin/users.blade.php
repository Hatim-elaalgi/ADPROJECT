@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/user-management.css') }}" rel="stylesheet">
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
                        <i class="fas fa-users me-2"></i>Gestion des Utilisateurs
                    </h3>
                    <div class="d-flex gap-2">
                        <div class="search-box">
                            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-plus-circle me-2"></i>Ajouter un Utilisateur
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="userTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Date d'inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td data-label="ID">{{ $user->id }}</td>
                                    <td data-label="Nom">{{ $user->name }}</td>
                                    <td data-label="Email">{{ $user->email }}</td>
                                    <td data-label="Rôle">
                                        <span class="badge {{ 
                                            $user->role === 'admin' ? 'badge-admin' : 
                                            ($user->role === 'responsable_theme' ? 'badge-responsable' : 'badge-subscriber') 
                                        }}">
                                            @switch($user->role)
                                                @case('admin')
                                                    Administrateur
                                                    @break
                                                @case('responsable_theme')
                                                    Responsable Thème
                                                    @break
                                                @default
                                                    Abonné
                                            @endswitch
                                        </span>
                                    </td>
                                    <td data-label="Date d'inscription">{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td data-label="Actions">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" data-bs-toggle="tooltip" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger delete-user" data-bs-toggle="tooltip" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Ajouter un Utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback">Veuillez entrer un nom</div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Veuillez entrer un email valide</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="password-strength-meter">
                            <div class="progress-bar"></div>
                        </div>
                        <div class="invalid-feedback">Veuillez entrer un mot de passe</div>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="subscriber">Abonné</option>
                            <option value="responsable_theme">Responsable Thème</option>
                            <option value="admin">Administrateur</option>
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner un rôle</div>
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

@foreach($users as $user)
<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">
                    <i class="fas fa-user-edit me-2"></i>Modifier l'Utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name{{ $user->id }}" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="edit_name{{ $user->id }}" name="name" value="{{ $user->name }}" required>
                        <div class="invalid-feedback">Veuillez entrer un nom</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email{{ $user->id }}" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email{{ $user->id }}" name="email" value="{{ $user->email }}" required>
                        <div class="invalid-feedback">Veuillez entrer un email valide</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password{{ $user->id }}" class="form-label">Nouveau mot de passe (laisser vide si inchangé)</label>
                        <input type="password" class="form-control" id="edit_password{{ $user->id }}" name="password">
                        <div class="password-strength-meter">
                            <div class="progress-bar"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role{{ $user->id }}" class="form-label">Rôle</label>
                        <select class="form-select" id="edit_role{{ $user->id }}" name="role" required>
                            <option value="subscriber" {{ $user->role === 'subscriber' ? 'selected' : '' }}>Abonné</option>
                            <option value="responsable_theme" {{ $user->role === 'responsable_theme' ? 'selected' : '' }}>Responsable Thème</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrateur</option>
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner un rôle</div>
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
<script src="{{ asset('js/user-management.js') }}"></script>
@endsection
