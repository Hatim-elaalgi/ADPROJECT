<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Theme;
use App\Models\Article;
use App\Models\Numero;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $stats = [
            'users' => [
                'total' => User::count(),
                'subscribers' => User::where('role', User::ROLE_SUBSCRIBER)->count(),
                'theme_managers' => User::where('role', User::ROLE_RESPONSABLE_THEME)->count(),
            ],
            'themes' => [
                'total' => Theme::count(),
                'active' => Theme::where('is_accept', true)->count(),
                'pending' => Theme::where('is_accept', false)->count(),
            ],
            'articles' => [
                'total' => Article::count(),
                'published' => Article::where('status', 'Publié')->count(),
                'pending' => Article::where('status', 'En cours')->count(),
            ],
        ];

        $recentActivities = collect([
            'users' => User::latest()->take(5)->get(),
            'themes' => Theme::latest()->take(5)->get(),
            'articles' => Article::latest()->take(5)->get(),
            'comments' => Commentaire::latest()->take(5)->get(),
        ]);

        $users = User::orderBy('name')->get();

        $themes = Theme::with(['themeManager', 'articles', 'users'])
            ->withCount(['articles', 'users as subscribers_count'])
            ->orderBy('created_at', 'desc')
            ->get();

        $themeManagers = User::where('role', User::ROLE_RESPONSABLE_THEME)
            ->orderBy('name')
            ->get();

        $popularThemes = Theme::withCount('users')
            ->orderBy('users_count', 'desc')
            ->take(5)
            ->get();

        $topArticles = Article::withCount(['ratings as average_rating' => function($query) {
            $query->select(DB::raw('coalesce(avg(rating),0)'));
        }])
        ->orderBy('average_rating', 'desc')
        ->take(5)
        ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentActivities',
            'popularThemes',
            'topArticles',
            'users',
            'themes',
            'themeManagers'
        ));
    }

    public function users()
    {
        $users = User::withCount(['themes', 'subscribedThemes', 'articles'])
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,blocked'
        ]);

        $user->status = $request->status;
        $user->save();

        return back()->with('success', 'Statut de l\'utilisateur mis à jour avec succès');
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:' . implode(',', [
                User::ROLE_SUBSCRIBER,
                User::ROLE_RESPONSABLE_THEME,
                User::ROLE_INVITE
            ])
        ]);

        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'Rôle de l\'utilisateur mis à jour avec succès');
    }

    public function deleteUser(User $user)
    {
        if ($user->role === User::ROLE_ADMIN) {
            return back()->with('error', 'Impossible de supprimer un administrateur');
        }

        $user->delete();
        return back()->with('success', 'Utilisateur supprimé avec succès');
    }

    public function articles()
    {
        $articles = Article::with(['user', 'theme'])
            ->withCount(['ratings as average_rating' => function($query) {
                $query->select(DB::raw('coalesce(avg(rating),0)'));
            }])
            ->latest()
            ->paginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    public function toggleArticle(Article $article)
    {
        $article->status = $article->status === 'Publié' ? 'En cours' : 'Publié';
        $article->save();

        return back()->with('success', 'Statut de l\'article mis à jour avec succès');
    }

    public function numeros()
    {
        $numeros = Numero::with(['articles'])
            ->latest()
            ->paginate(10);

        return view('admin.numeros.index', compact('numeros'));
    }

    public function toggleNumero(Numero $numero)
    {
        $numero->is_active = !$numero->is_active;
        $numero->save();

        return back()->with('success', 'Statut du numéro mis à jour avec succès');
    }

    public function themes()
    {
        $themes = Theme::with(['themeManager', 'articles', 'users'])
            ->withCount(['articles', 'users as subscribers_count'])
            ->orderBy('created_at', 'desc')
            ->get();

        $themeManagers = User::where('role', User::ROLE_RESPONSABLE_THEME)
            ->orderBy('name')
            ->get();

        return view('admin.themes', [
            'themes' => $themes,
            'themeManagers' => $themeManagers
        ]);
    }

    public function toggleTheme(Theme $theme)
    {
        // Définir le prochain statut en fonction du statut actuel
        $nextStatus = match($theme->status) {
            Theme::STATUS_EN_COURS => Theme::STATUS_PUBLIE,
            Theme::STATUS_PUBLIE => Theme::STATUS_RETENU,
            Theme::STATUS_RETENU => Theme::STATUS_EN_COURS,
            Theme::STATUS_REFUSE => Theme::STATUS_EN_COURS,
            default => Theme::STATUS_EN_COURS
        };

        // Mettre à jour le statut
        $theme->status = $nextStatus;
        
        // Mettre à jour is_accept en fonction du nouveau statut
        $theme->is_accept = in_array($nextStatus, [Theme::STATUS_PUBLIE, Theme::STATUS_RETENU]);
        
        // Sauvegarder les modifications
        $saved = $theme->save();

        // Message de débogage
        $message = $saved 
            ? "Statut mis à jour avec succès (Ancien: {$theme->getOriginal('status')}, Nouveau: {$nextStatus})"
            : "Erreur lors de la mise à jour du statut";

        return back()->with($saved ? 'success' : 'error', $message);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'theme_manager_id' => 'required|exists:users,id',
            'status' => 'required|in:en_cours,publie,refuse,retenu'
        ]);

        try {
            DB::beginTransaction();

            // Création du thème
        $theme = Theme::create([
            'title' => $request->title,
                'discription' => $request->description,
            'user_id' => $request->theme_manager_id,
            'status' => $request->status,
            'is_accept' => in_array($request->status, ['publie', 'retenu'])
        ]);

            // Abonnement automatique du responsable au thème
            $theme->users()->attach($request->theme_manager_id);

            DB::commit();

            return redirect()->back()->with('success', 'Thème créé avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Erreur lors de la création du thème : ' . $e->getMessage());
        }
    }

    public function update(Request $request, Theme $theme)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'theme_manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:en_cours,publie,refuse,retenu'
        ]);

        try {
            DB::beginTransaction();

            $data = [];
            
            if ($request->has('title')) {
                $data['title'] = $request->title;
            }
            
            if ($request->has('description')) {
                $data['discription'] = $request->description;
            }
            
            if ($request->has('theme_manager_id')) {
                $data['user_id'] = $request->theme_manager_id;
            }

            // Mettre à jour le statut
            $data['status'] = $request->status;
            $data['is_accept'] = in_array($request->status, ['publie', 'retenu']);

            // Si le thème est accepté (publié ou retenu) et que le créateur était un subscriber
            if ($data['is_accept'] && $theme->themeManager->role === User::ROLE_SUBSCRIBER) {
                // Promouvoir le subscriber en theme manager
                $theme->themeManager->update([
                    'role' => User::ROLE_RESPONSABLE_THEME
                ]);
            }

            $theme->update($data);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thème mis à jour avec succès',
                    'theme' => $theme
                ]);
            }

            return redirect()->back()->with('success', 'Thème mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function destroy(Theme $theme)
    {
        // Delete associated articles and their relationships first
        foreach ($theme->articles as $article) {
            $article->delete();
        }
        
        $theme->delete();
        return redirect()->route('admin.themes')->with('success', 'Thème supprimé avec succès.');
    }

    public function show(Theme $theme)
    {
        $theme->load(['user', 'articles.user', 'users']);
        return view('admin.theme-details', compact('theme'));
    }
}
