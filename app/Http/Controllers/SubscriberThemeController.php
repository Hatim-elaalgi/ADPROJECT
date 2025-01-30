<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriberThemeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:subscriber']);
    }

    public function create()
    {
        return view('subscriber.propose-theme');
    }

    public function propose(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            // Créer le thème avec le statut en_cours
            $theme = Theme::create([
                'title' => $request->title,
                'discription' => $request->description,
                'user_id' => Auth::id(),
                'status' => Theme::STATUS_EN_COURS,
                'is_accept' => false
            ]);

            // Abonner automatiquement l'utilisateur au thème
            $theme->users()->attach(Auth::id());

            DB::commit();

            return redirect()->back()->with('success', 'Votre proposition de thème a été soumise avec succès. Elle sera examinée par l\'administrateur.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la soumission de votre proposition.');
        }
    }
} 