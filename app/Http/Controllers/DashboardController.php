<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === User::ROLE_RESPONSABLE_THEME) {
            // For theme manager, show their managed themes
            $managedThemes = $user->managedThemes()->get();
            return view('dashboard.theme_manager', compact('managedThemes'));
        } else if ($user->role === User::ROLE_SUBSCRIBER) {
            // For subscriber, show create theme message
            return view('dashboard.subscriber');
        }

        // Default fallback
        return redirect()->route('home');
    }
}
