<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Usuario::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->hash_contrasena)) {
            Auth::login($user, $request->boolean('remember'));
            $user->update(['ultimo_acceso' => now()]);
            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:100',
            'email' => 'required|email|unique:usuario,email',
            'telefono' => 'required|string|max:20',
            'password' => 'required|min:6|confirmed',
            'rol' => 'required|in:pasajero,conductor',
        ]);

        $user = Usuario::create([
            'nombre_usuario' => explode('@', $request->email)[0],
            'nombre_completo' => $request->nombre_completo,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'hash_contrasena' => Hash::make($request->password),
            'activo' => true,
            'rol' => $request->rol,
            'fecha_creacion' => now(),
        ]);

        // Crear perfil según rol
        if ($request->rol === 'pasajero') {
            \App\Models\Pasajero::create([
                'id_usuario' => $user->id_usuario,
                'metodo_pago_default' => 'efectivo',
                'calificacion_promedio' => 5.0,
            ]);
        } elseif ($request->rol === 'conductor') {
            \App\Models\Conductor::create([
                'id_usuario' => $user->id_usuario,
                'licencia_conducir' => 'PEND-' . $user->id_usuario,
                'calificacion_promedio' => 5.0,
                'disponible' => true,
                'estado' => 'activo',
            ]);
        }

        Auth::login($user);
        return redirect('/dashboard');
    }
}
