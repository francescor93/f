<?php

namespace App\Http\Controllers;

use App\Models\User;

class ProfileController extends Controller {
    public function show(string $user = '') {
        $user = ($user) ? User::where('username', '=', $user)->first() : auth()->user();
        if (!$user) {
            abort(404);
        }
        return view('profile')->with(['user' => $user]);
    }
}
