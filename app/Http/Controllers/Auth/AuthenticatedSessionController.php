<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Mahasiswa;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('user', 'password');
        $loginInput = $credentials['user'];
        $isNIM = is_numeric($loginInput);

    // Jika input adalah angka (NIM), prioritaskan cek tabel Mahasiswa
        if ($isNIM) {
            $mahasiswa = Mahasiswa::where('nim', $loginInput)->first();
            if ($mahasiswa && Hash::check($credentials['password'], $mahasiswa->password)) {
                Auth::guard('mahasiswa')->login($mahasiswa, $request->boolean('remember'));
                $request->session()->regenerate();
                // Redirect mahasiswa ke dashboard mereka
                return redirect()->intended('/dashboard');
            }
        }
        // Jika bukan NIM, atau login mahasiswa gagal, cek tabel Users (Admin)
        else {
            $user = User::where('username', $loginInput)->first();
            if ($user && Hash::check($credentials['password'], $user->password)) {
                Auth::guard('web')->login($user, $request->boolean('remember'));
                $request->session()->regenerate();
                // Redirect admin ke halaman surat masuk
                return redirect()->intended('/surat-masuk');
            }
        }

        // Jika semua gagal, kembalikan error
        return back()->withErrors([
            'user' => 'NIM/Username atau Password salah.',
        ])->onlyInput('user');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $guard = Auth::guard('web')->check() ? 'web' : 'mahasiswa';
        Auth::guard($guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}