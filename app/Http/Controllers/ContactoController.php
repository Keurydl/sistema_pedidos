<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    /**
     * Display the contact form.
     */
    public function index()
    {
        return view('contacto');
    }

    /**
     * Process the contact form submission.
     */
    public function enviar(Request $request)
    {
        // Validate the form data
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ]);

        // Here you would typically send an email
        // For now, we'll just redirect back with a success message
        
        return redirect()->back()->with('success', 'Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.');
    }
}