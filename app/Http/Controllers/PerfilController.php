<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PerfilController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        $usuario = Auth::user();
        
        if (!$usuario) {
            return redirect()->route('login');
        }
        
        return view('perfil.index', compact('usuario'));
    }
    
    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $usuario = Auth::user();
        
        if (!$usuario) {
            return redirect()->route('login');
        }
        
        return view('perfil.edit', compact('usuario'));
    }
    
    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $usuario = Auth::user();
        
        if (!$usuario) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);
        
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
        $usuario->direccion = $request->direccion;
        $usuario->save();
        
        return redirect()->route('perfil.index')->with('success', 'Perfil actualizado correctamente.');
    }
    
    /**
     * Show the form for changing the user's password.
     */
    public function showChangePasswordForm()
    {
        return view('perfil.change-password');
    }
    
    /**
     * Change the user's password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $usuario = Auth::user();
        
        if (!Hash::check($request->current_password, $usuario->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }
        
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        
        return redirect()->route('perfil.index')->with('success', 'Contraseña cambiada correctamente.');
    }
}