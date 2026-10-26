<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials['is_active'] = true;

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password tidak valid, atau akun tidak aktif.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        AuditLog::catat(
            'Autentikasi',
            'Login',
            "Pengguna " . Auth::user()->name . " (" . Auth::user()->email . ") berhasil login sebagai " . strtoupper(Auth::user()->role)
        );

        return redirect()->intended(route('dashboard'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->role = 'pasien';
            $user->is_active = true;
            $user->save();

            do {
                $nomorRm = 'RM' . Str::upper(Str::random(16));
            } while (
                Pasien::where('nomor_rm', $nomorRm)->exists()
            );

            Pasien::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'nomor_rm' => $nomorRm,
            ]);

            return $user;
        });

        Auth::login($user);

        $request->session()->regenerate();

        AuditLog::catat(
            'Autentikasi',
            'Registrasi Pasien Baru',
            "Pasien baru {$user->name} ({$user->email}) berhasil mendaftar akun mandiri",
            $user
        );

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil. Selamat datang.');
    }


    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            AuditLog::catat(
                'Autentikasi',
                'Logout',
                "Pengguna {$user->name} ({$user->email}) keluar dari sistem",
                $user
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout.');
    }
}