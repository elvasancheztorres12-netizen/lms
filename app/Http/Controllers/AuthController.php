<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Person;
use App\Models\Role;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Modificamos las reglas para los nuevos campos del formulario
        $request->validate([
            'first_names' => 'required|string|max:20',
            'last_names' => 'required|string|max:20',
            'document_number' => 'required|string|max:20',
            'email' => 'required|email|max:150',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 2. Filtro de Entrada: Verificamos si la persona ya existe por DNI y Correo (Pre-matrícula)
        $person = Person::where('document_number', $request->document_number)
                        ->where('email', $request->email)
                        ->first();

        if (!$person) {
            return back()->withInput()->with('error', 'No se encontró ninguna matrícula activa con el DNI y correo ingresados. Por favor, regularice su pago.');
        }

        // 3. Verificamos que esa persona no tenga ya una cuenta creada para evitar duplicados
        $userExists = User::where('person_id', $person->person_id)->exists();

        if ($userExists) {
            return back()->withInput()->with('error', 'Este documento ya cuenta con un usuario activo en el sistema. Intente iniciar sesión.');
        }

        // 4. Procesamos el registro seguro con el Try-Catch y Transacción que ya tenías
        DB::beginTransaction();

        try {
            // Actualizamos nombres por si acaso hubo un cambio en el registro físico/formulario
            $person->update([
                'first_names' => trim($request->first_names),
                'last_names' => trim($request->last_names),
            ]);

            // Creamos las credenciales del alumno utilizando su nuevo 'username'
            $user = User::create([
                'person_id' => $person->person_id,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'status' => 'A',
            ]);

            // Asignación segura del rol Student
            $studentRole = Role::where('name', 'Student')->first();
            if (!$studentRole) {
                throw new \Exception('El rol "Student" no está configurado en el sistema. Contacte al administrador.');
            }
            $user->roles()->attach($studentRole->role_id);

            DB::commit();

            return redirect()->route('login')
                ->with('success', '¡Registro completado con éxito! Ahora puedes iniciar sesión.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors([
                'email' => 'Ocurrió un error al procesar el registro: ' . $e->getMessage()
            ]);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Forzamos que solo entren usuarios activos
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'status' => 'A'
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Mapeo seguro con colecciones para evitar errores multi-rol
            if ($user->roles->contains('name', 'Administrator')) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->roles->contains('name', 'Teacher')) {
                return redirect()->route('teacher.dashboard');
            }

            if ($user->roles->contains('name', 'Student')) {
                return redirect()->route('student.dashboard');
            }

            Auth::logout();
            return back()->withErrors([
                'username' => 'Usuario sin rol válido asignado. Contacte al administrador.'
            ]);
        }

        return back()->withErrors([
            'username' => 'Las credenciales no coinciden o el usuario se encuentra inactivo.'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}