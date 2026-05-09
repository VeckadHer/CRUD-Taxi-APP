<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Pasajero;
use App\Models\SolicitudConductor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm() { return view('auth.login'); }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = strtolower(trim($request->email));

        $user = Usuario::where('email', $email)->first();
        if ($user && Hash::check($request->password, $user->hash_contrasena)) {
            // Validar coincidencia rol-dominio (excepto admin)
            if ($user->rol === 'pasajero' && !str_ends_with($email, '@gmail.com')) {
                return back()->withErrors(['email' => 'Las cuentas de pasajero deben usar @gmail.com'])->withInput();
            }
            if ($user->rol === 'conductor' && !str_ends_with($email, '@driver.com')) {
                return back()->withErrors(['email' => 'Las cuentas de conductor deben usar @driver.com'])->withInput();
            }

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

    public function showRegister() { return view('auth.register'); }

    /**
     * Registro SOLO para pasajeros con email @gmail.com
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido_paterno' => 'required|string|max:50',
            'apellido_materno' => 'required|string|max:50',
            'fecha_nacimiento' => 'required|date|before_or_equal:' . date('Y-m-d', strtotime('-18 years')),
            'email' => [
                'required', 'email', 'unique:usuario,email',
                function ($attr, $value, $fail) {
                    if (!str_ends_with(strtolower($value), '@gmail.com')) {
                        $fail('El email debe ser @gmail.com para registrarse como pasajero.');
                    }
                },
            ],
            'telefono' => 'required|string|max:20',
            'domicilio' => 'required|string|max:200',
            'codigo_postal' => 'required|string|max:10',
            'password' => 'required|min:6|confirmed',
        ], [
            'fecha_nacimiento.before_or_equal' => 'Debes ser mayor de 18 años para registrarte',
        ]);

        $user = Usuario::create([
            'nombre_usuario' => 'usr_' . strtolower(explode('@', $request->email)[0]),
            'nombre_completo' => trim($request->nombre . ' ' . $request->apellido_paterno . ' ' . $request->apellido_materno),
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'email' => strtolower($request->email),
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'domicilio' => $request->domicilio,
            'codigo_postal' => $request->codigo_postal,
            'hash_contrasena' => Hash::make($request->password),
            'fecha_creacion' => now(),
            'activo' => true,
            'rol' => 'pasajero',
        ]);

        Pasajero::create([
            'id_usuario' => $user->id_usuario,
            'metodo_pago_default' => 'efectivo',
            'calificacion_promedio' => 5.0,
        ]);

        Auth::login($user);
        return redirect('/dashboard')->with('mensaje', '¡Bienvenido! Tu cuenta fue creada exitosamente.');
    }

    public function showSolicitudConductor()
    {
        return view('auth.solicitud_conductor');
    }

    public function storeSolicitudConductor(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:150',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|email',
            'mensaje' => 'nullable|string|max:500',
        ]);

        SolicitudConductor::create([
            'nombre_completo' => $request->nombre_completo,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'mensaje' => $request->mensaje,
            'estado' => 'pendiente',
        ]);

        return redirect('/login')->with('mensaje', '✓ Tu solicitud fue enviada. Te contactaremos pronto al teléfono proporcionado.');
    }
}
