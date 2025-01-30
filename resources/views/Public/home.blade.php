<html>
<head>
    <title>After login page</title>
    <link rel="stylesheet" href="{{asset('css/public/home.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>
<body>
    <div class="banner">
        <div class="navbar">
            <img src="{{asset('images/homePublic/logo.png')}}" class="logo">
            <ul>
                <li><a href="{{ route('hometest') }}">Home</a></li>
               
                    <li class="dropdown">
                        <a href="{{ route('mes_themes') }}">Mes themes</a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Test Theme</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown1">
                        <a href="{{ route('themes') }}">Tous les Themes</a>
                        <ul class="dropdown-theme">
                            <li><a href="#">Test Theme</a></li>
                        </ul>
                    </li>
                
                @if(Auth::user()->role === 'responsable_theme')
                    <li><a href="#" id="gest-btn">gestionnaire de themes</a></li>
                @endif
                <li><a href="#" id="my-articles-btn">My articles</a></li>
            </ul>
            <div class="user-actions">
                <a href="{{ route('subscriber.dashboard') }}" class="profile-button">
                    <img id="user-avatar" src="{{asset('images/homePublic/default-logo.png')}}" alt="User Avatar">
                    <p>{{ Auth::user()->name }}</p>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-button"><span class="highlight"></span>LOG OUT</button>
                </form>
            </div>
            
                    </div>
        <div class="content">
            <h1 id="welcome-text">Welcome, {{ Auth::user()->name }}!</h1>
            <p>"Ready to explore the future? Let's dive into the latest tech insights waiting for you!"</p>
        </div>
        <div id="articles-section" style="display: none;">
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom Article</th>
                        <th>Theme</th>
                        <th>État de Diffusion</th>
                        <th>Rating</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody id="articles-table-body">
                    <!-- Les lignes seront ajoutées dynamiquement via JavaScript -->
                </tbody>
            </table>
            <button id="add-article-btn">Add Article</button>
            <div id="add-article-form" style="display: none;">
                <form>
                    <label for="article-name">Nom de l'article:</label>
                    <input type="text" id="article-name" required>
                    
                    <label for="article-content">Texte de l'article:</label>
                    <textarea id="article-content" rows="5" required></textarea>
                    
                    <label for="article-theme">Sélectionnez un thème:</label>
                    <select id="article-theme" required>
                        <!-- Les options seront ajoutées dynamiquement via JavaScript -->
                    </select>
                    
                    <button type="submit" id="submit-article-btn">Ajouter l'article</button>
                </form>
            </div>

        </div>
        <div id="article-details-section" style="display: none;">
            <div class="article-details">
                <h1 id="article-title"></h1>
                <p id="article-content"></p>
                <img id="article-image" src="" alt="Article Image">
                <div class="article-rating">
                    <p>Rate this article:</p>
                    <div id="rating-stars">
                        <!-- Les étoiles seront générées dynamiquement avec JavaScript -->
                    </div>
                    <p id="user-rating-feedback"></p>
                </div>
            </div>
        </div>
        <div id="theme-articles-section" style="display: none;">
            <table>
                <thead>
                    <tr>
                        <th>Nom Article</th>
                        <th>Rating</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="theme-articles-table-body">
                    <!-- Les lignes seront ajoutées dynamiquement via JavaScript -->
                </tbody>
            </table>
        </div>
        <div id="tous-theme" style="display: none;">
            <table>
                <thead>
                    <tr>
                        <th>Nom Article</th>
                        <th>Rating</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tous-themes-table-body">
                    <!-- Les lignes seront ajoutées dynamiquement via JavaScript -->
                </tbody>
            </table>
        </div>
        <div id="theme-manager-section" style="display: none;">
            <table>
                <thead>
                    <tr>
                        <th>Nom du Thème</th>
                        <th>Ajouter</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody id="theme-manager-table-body">
                    <!-- Les lignes seront ajoutées dynamiquement avec JavaScript -->
                </tbody>
            </table>
        </div>
        
        </div>
        
    </div>



    <script src="script.js"></script>
</body>
</html>