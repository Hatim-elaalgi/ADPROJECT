<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function myThemes()
    {
        $user = Auth::user();
        $themes = [];
        
        if ($user->role === User::ROLE_RESPONSABLE_THEME) {
            // Pour les responsables, montrer tous leurs thèmes sans filtrer par statut
            $themes = Theme::where('user_id', $user->id)
                ->with(['users'])
                ->get();
        } elseif ($user->role === User::ROLE_SUBSCRIBER) {
            // Pour les abonnés, montrer uniquement les thèmes publiés auxquels ils sont abonnés
            $themes = $user->subscribedThemes()
                ->where('status', 'publie')
                ->get();
        }

        return view('themes.my_themes', [
            'themes' => $themes,
            'userRole' => $user->role
        ]);
    }

    public function allThemes()
    {
        $user = Auth::user();
        
        // If user is not an invite, show published themes only
        if ($user->role !== User::ROLE_INVITE) {
            $themes = Theme::with(['themeManager', 'users'])
                ->where('status', 'publie')  // Filtre uniquement les thèmes publiés
                ->get();
        } else {
            return redirect()->route('home')->with('error', 'Access denied');
        }

        return view('themes.all_themes', [
            'themes' => $themes,
            'userRole' => $user->role
        ]);
    }

    public function subscribe(Theme $theme)
    {
        // Check if user is already subscribed
        if ($theme->users->contains(Auth::id())) {
            return redirect()->back()->with('error', 'Vous êtes déjà abonné à ce thème');
        }

        // Add user to theme subscribers
        $theme->users()->attach(Auth::id());

        return redirect()->back()->with('success', 'Vous êtes maintenant abonné au thème');
    }

    public function unsubscribe(Theme $theme)
    {
        // Check if user is subscribed
        if (!$theme->users->contains(Auth::id())) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas abonné à ce thème');
        }

        // Remove user from theme subscribers
        $theme->users()->detach(Auth::id());

        return redirect()->back()->with('success', 'Vous êtes maintenant désabonné du thème');
    }
}
