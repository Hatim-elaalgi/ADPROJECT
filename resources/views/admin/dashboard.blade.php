<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tableau de bord - Administrateur</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<style>
        /* Variables CSS */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --danger-color: #e74c3c;
            --background-color: #f5f6fa;
            --text-color: #2c3e50;
            --border-color: #ddd;
            --shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        /* Reset et Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Layout */
        .dashboard-container {
        display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background-color: white;
            padding: 2rem;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }

        .user-info {
            text-align: center;
        margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .user-avatar {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            transition: var(--transition);
        }

        .user-avatar:hover {
            transform: scale(1.1);
            color: var(--secondary-color);
        }

        .user-info h2 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .role {
            display: inline-block;
            padding: 0.25rem 1rem;
            background-color: var(--primary-color);
            color: white;
            border-radius: 15px;
            font-size: 0.9rem;
        }

        /* Navigation */
        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .sidebar-nav a {
            padding: 1rem;
            text-decoration: none;
        color: var(--text-color);
            border-radius: 8px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-nav a:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        .sidebar-nav a.active {
            background-color: var(--primary-color);
            color: white;
        }

        .sidebar-nav a i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .content-section {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .content-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Stats Grid */
        .stats-grid {
        display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
            background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 1rem;
        margin-bottom: 1rem;
        }

        .stat-icon {
            font-size: 1.5rem;
            color: var(--primary-color);
            transition: var(--transition);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.2);
    }

    .stat-numbers {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        gap: 1rem;
    }

    .stat-number {
        text-align: center;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-color);
            animation: pulse 2s infinite;
    }

    .stat-label {
        font-size: 0.875rem;
            color: #666;
        }

        /* Tables */
        .data-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .data-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .data-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .data-table tr {
            transition: var(--transition);
        }

        .data-table tr:hover {
            background-color: #f8f9fa;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: var(--transition);
            margin: 0 0.25rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Activity Feed */
        .activity-feed {
            background: white;
        border-radius: 8px;
        box-shadow: var(--shadow);
            padding: 1.5rem;
    }

        .activity-feed h3 {
        margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
    }

    .activity-item {
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
    }

        .activity-item:hover {
            background-color: #f8f9fa;
            padding-left: 1rem;
    }

    .activity-meta {
        font-size: 0.875rem;
            color: #666;
        margin-top: 0.25rem;
    }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .data-table {
                overflow-x: auto;
            }
        }

        /* Animations */
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* SVG Icons Styles */
        .icon {
            width: 24px;
            height: 24px;
            fill: currentColor;
        }

        .icon-large {
            width: 48px;
            height: 48px;
    }

    .inline-form {
        display: inline-block;
        margin: 0;
    }

    .role-select,
    .status-select {
        padding: 0.5rem;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        background-color: white;
        font-size: 0.9rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .role-select:hover,
    .status-select:hover {
        border-color: var(--primary-color);
    }

    .role-select:focus,
    .status-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
    }

    .btn svg {
        width: 18px;
        height: 18px;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border-color);
    }

    .close {
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        color: #666;
    }

    .close:hover {
        color: var(--primary-color);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 14px;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
    }

    .modal-footer {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .btn-secondary {
        background-color: #95a5a6;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #7f8c8d;
    }

    /* Ajout des styles pour le textarea */
    .form-group textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 14px;
        resize: vertical;
        min-height: 100px;
    }

    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
    }
</style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="user-info">
                <div class="user-avatar">
                    <svg class="icon icon-large" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
    </div>
                <h2>{{ Auth::user()->name }}</h2>
                <span class="role">Administrateur</span>
</div>

            <nav class="sidebar-nav">
                <a href="#dashboard" class="active" data-section="dashboard">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                    </svg>
                    Tableau de bord
                </a>
                <a href="#users" data-section="users">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                    </svg>
                    Utilisateurs
                </a>
                <a href="#themes" data-section="themes">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12zM12 5.5v9l6-4.5z"/>
                    </svg>
                    Thèmes
                </a>
                <a href="#articles" data-section="articles">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.96-2.36L6.5 17h11l-3.54-4.71z"/>
                    </svg>
                    Articles
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                    Déconnexion
                </a>
            </nav>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Dashboard Section -->
            <section id="dashboard" class="content-section active">
                <div class="stats-grid">
    <div class="stat-card">
                        <div class="stat-header">
                            <svg class="icon" viewBox="0 0 24 24">
                                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                            </svg>
        <h3>Utilisateurs</h3>
                        </div>
        <div class="stat-numbers">
            <div class="stat-number">
                <div class="stat-value">{{ $stats['users']['total'] }}</div>
                <div class="stat-label">Total</div>
            </div>
            <div class="stat-number">
                <div class="stat-value">{{ $stats['users']['subscribers'] }}</div>
                <div class="stat-label">Abonnés</div>
            </div>
            <div class="stat-number">
                <div class="stat-value">{{ $stats['users']['theme_managers'] }}</div>
                <div class="stat-label">Responsables</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
                        <div class="stat-header">
                            <svg class="icon" viewBox="0 0 24 24">
                                <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12zM12 5.5v9l6-4.5z"/>
                            </svg>
        <h3>Thèmes</h3>
                        </div>
        <div class="stat-numbers">
            <div class="stat-number">
                <div class="stat-value">{{ $stats['themes']['total'] }}</div>
                <div class="stat-label">Total</div>
            </div>
            <div class="stat-number">
                <div class="stat-value">{{ $stats['themes']['active'] }}</div>
                <div class="stat-label">Actifs</div>
            </div>
            <div class="stat-number">
                <div class="stat-value">{{ $stats['themes']['pending'] }}</div>
                <div class="stat-label">En attente</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
                        <div class="stat-header">
                            <svg class="icon" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.96-2.36L6.5 17h11l-3.54-4.71z"/>
                            </svg>
        <h3>Articles</h3>
                        </div>
        <div class="stat-numbers">
            <div class="stat-number">
                <div class="stat-value">{{ $stats['articles']['total'] }}</div>
                <div class="stat-label">Total</div>
            </div>
            <div class="stat-number">
                <div class="stat-value">{{ $stats['articles']['published'] }}</div>
                <div class="stat-label">Publiés</div>
            </div>
            <div class="stat-number">
                <div class="stat-value">{{ $stats['articles']['pending'] }}</div>
                <div class="stat-label">En attente</div>
            </div>
        </div>
    </div>
</div>

                <div class="activity-feed">
        <h3>Activités récentes</h3>
            @foreach($recentActivities['users'] as $user)
                    <div class="activity-item">
                <div>Nouvel utilisateur : {{ $user->name }}</div>
                <div class="activity-meta">{{ $user->created_at->diffForHumans() }}</div>
                    </div>
            @endforeach

            @foreach($recentActivities['themes'] as $theme)
                    <div class="activity-item">
                <div>Nouveau thème : {{ $theme->title }}</div>
                <div class="activity-meta">{{ $theme->created_at->diffForHumans() }}</div>
                    </div>
            @endforeach

            @foreach($recentActivities['articles'] as $article)
                    <div class="activity-item">
                <div>Nouvel article : {{ $article->title }}</div>
                <div class="activity-meta">{{ $article->created_at->diffForHumans() }}</div>
                    </div>
            @endforeach
                </div>
            </section>

            <!-- Users Section -->
            <section id="users" class="content-section">
                <div class="section-header">
                    <h2>Gestion des Utilisateurs</h2>
                    <button class="btn btn-primary" onclick="openModal('newUserModal')">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                        </svg>
                        Ajouter un utilisateur
                    </button>
    </div>

                <!-- Modal pour ajouter un utilisateur -->
                <div id="newUserModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Ajouter un nouvel utilisateur</h3>
                            <span class="close" onclick="closeModal('newUserModal')">&times;</span>
                        </div>
                        <form action="{{ route('admin.users.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Nom</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <input type="password" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Rôle</label>
                                <select id="role" name="role" required>
                                    <option value="subscriber">Abonné</option>
                                    <option value="responsable_theme">Responsable</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="closeModal('newUserModal')">Annuler</button>
                                <button type="submit" class="btn btn-primary">Créer</button>
                            </div>
                        </form>
                    </div>
        </div>

                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            @if($user->id !== Auth::id())
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" onchange="this.form.submit()" class="role-select">
                                            <option value="subscriber" {{ $user->role === 'subscriber' ? 'selected' : '' }}>Abonné</option>
                                            <option value="responsable_theme" {{ $user->role === 'responsable_theme' ? 'selected' : '' }}>Responsable</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="status-select">
                                            <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Actif</option>
                                            <option value="blocked" {{ $user->status === 'blocked' ? 'selected' : '' }}>Bloqué</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <svg class="icon" viewBox="0 0 24 24">
                                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Themes Section -->
            <section id="themes" class="content-section">
                <div class="section-header">
                    <h2>Gestion des Thèmes</h2>
                    <button class="btn btn-primary" onclick="openModal('newThemeModal')">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                        </svg>
                        Ajouter un thème
                    </button>
                </div>

                <!-- Modal pour ajouter un thème -->
                <div id="newThemeModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Ajouter un nouveau thème</h3>
                            <span class="close" onclick="closeModal('newThemeModal')">&times;</span>
                        </div>
                        <form action="{{ route('admin.themes.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="title">Titre</label>
                                <input type="text" id="title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" required rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="theme_manager_id">Responsable du thème</label>
                                <select id="theme_manager_id" name="theme_manager_id" required>
                                    @foreach($themeManagers as $manager)
                                        <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Statut</label>
                                <select id="status" name="status" required>
                                    <option value="en_cours">En cours</option>
                                    <option value="publie">Publié</option>
                                    <option value="retenu">Retenu</option>
                                    <option value="refuse">Refusé</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="closeModal('newThemeModal')">Annuler</button>
                                <button type="submit" class="btn btn-primary">Créer</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Description</th>
                                <th>Responsable</th>
                                <th>Articles</th>
                                <th>Abonnés</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($themes as $theme)
                            <tr>
                                <td>{{ $theme->title }}</td>
                                <td>{{ Str::limit($theme->description, 50) }}</td>
                                <td>{{ $theme->themeManager->name }}</td>
                                <td>{{ $theme->articles_count }}</td>
                                <td>{{ $theme->subscribers_count }}</td>
                                <td>
                                    <form action="{{ route('admin.themes.update', $theme->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="status-select">
                                            <option value="en_cours" {{ $theme->status === 'en_cours' ? 'selected' : '' }}>En cours</option>
                                            <option value="publie" {{ $theme->status === 'publie' ? 'selected' : '' }}>Publié</option>
                                            <option value="retenu" {{ $theme->status === 'retenu' ? 'selected' : '' }}>Retenu</option>
                                            <option value="refuse" {{ $theme->status === 'refuse' ? 'selected' : '' }}>Refusé</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <button class="btn btn-primary" onclick="openEditThemeModal({{ json_encode([
                                        'id' => $theme->id,
                                        'title' => $theme->title,
                                        'description' => $theme->description,
                                        'user_id' => $theme->user_id
                                    ]) }})">
                                        <svg class="icon" viewBox="0 0 24 24">
                                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.themes.delete', $theme->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce thème ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <svg class="icon" viewBox="0 0 24 24">
                                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal pour éditer un thème -->
                <div id="editThemeModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Modifier le thème</h3>
                            <span class="close" onclick="closeModal('editThemeModal')">&times;</span>
                        </div>
                        <form id="editThemeForm" action="" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="edit_title">Titre</label>
                                <input type="text" id="edit_title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_description">Description</label>
                                <textarea id="edit_description" name="description" required rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_theme_manager_id">Responsable du thème</label>
                                <select id="edit_theme_manager_id" name="theme_manager_id" required>
                                    @foreach($themeManagers as $manager)
                                        <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="closeModal('editThemeModal')">Annuler</button>
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Articles Section -->
            <section id="articles" class="content-section">
                <h2>Gestion des Articles</h2>
                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Auteur</th>
                                <th>Thème</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivities['articles'] as $article)
                            <tr>
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->user->name }}</td>
                                <td>{{ $article->theme->title }}</td>
                                <td>{{ $article->status }}</td>
                                <td>
                                    <button class="btn btn-primary"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                @endforeach
                        </tbody>
                    </table>
        </div>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation améliorée
            const navLinks = document.querySelectorAll('.sidebar-nav a');
            const sections = document.querySelectorAll('.content-section');

            navLinks.forEach(link => {
                if (!link.hasAttribute('onclick')) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Animation de transition
                        navLinks.forEach(l => {
                            l.classList.remove('active');
                            l.style.transition = 'all 0.3s ease';
                        });
                        
                        this.classList.add('active');
                        
                        const targetId = this.getAttribute('data-section');
                        sections.forEach(section => {
                            section.classList.remove('active');
                            if (section.id === targetId) {
                                section.classList.add('active');
                                // Ajoute une animation de fondu
                                section.style.animation = 'fadeIn 0.3s ease forwards';
                            }
                        });
                    });
                }
            });

            // Animation des statistiques
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(value => {
                value.style.animation = 'pulse 2s infinite';
            });

            // Effet de survol pour les lignes de tableau
            const tableRows = document.querySelectorAll('.data-table tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8f9fa';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });

            // Gestion des formulaires de mise à jour
            document.querySelectorAll('.role-select, .status-select').forEach(select => {
                select.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            });
        });

        // Ajout des fonctions pour le modal
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Fermer le modal si on clique en dehors
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Fonction pour ouvrir le modal d'édition avec les données du thème
        function openEditThemeModal(theme) {
            const modal = document.getElementById('editThemeModal');
            const form = document.getElementById('editThemeForm');
            form.action = `/admin/themes/${theme.id}`;
            
            document.getElementById('edit_title').value = theme.title;
            document.getElementById('edit_description').value = theme.description;
            document.getElementById('edit_theme_manager_id').value = theme.user_id;
            
            modal.style.display = 'block';
        }
    </script>
</body>
</html>
