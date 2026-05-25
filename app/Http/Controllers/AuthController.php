<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect('/dashboard');
            }
            return redirect('/dashboard-siswa');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function registerForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'kelas'    => 'required|string|max:50',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.unique'       => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 6 karakter.',
        ]);

        User::create([
            'name'     => $request->nama,
            'kelas'    => $request->kelas,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'siswa',
        ]);

        Auth::attempt($request->only('email', 'password'));
        $request->session()->regenerate();

        return redirect('/dashboard-siswa');
    }
}