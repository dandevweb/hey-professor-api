<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __invoke()
    {
        $data = request()->validate([
            'name'     => 'required|min:3|max:255',
            'email'    => 'required|min:3|max:255|email|unique:users|confirmed',
            'password' => 'required|min:8|max:40',
        ]);

        $user = User::create($data);

        Auth::login($user);

        return response()->json($user);
    }
}
