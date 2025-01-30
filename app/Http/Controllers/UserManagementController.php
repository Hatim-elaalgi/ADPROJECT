<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,responsable_theme,subscriber',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Utilisateur créé avec succès');
    }

    public function update(Request $request, User $user)
    {
        // Validation simplifiée pour permettre la mise à jour individuelle du rôle ou du statut
        if ($request->has('role')) {
            $request->validate([
                'role' => 'required|in:admin,responsable_theme,subscriber'
            ]);
            $user->update(['role' => $request->role]);
        }

        if ($request->has('status')) {
            $request->validate([
                'status' => 'required|in:active,blocked'
            ]);
            $user->update(['status' => $request->status]);
        }

        return redirect()->back()->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès');
    }
}
