<?php

namespace App\Http\Controllers;

use App\Models\Informe;
use App\Mail\InformeEnviado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EnvioController extends Controller
{
    public function formulario($carga_id)
    {
        $carga = \App\Models\CargaDatos::findOrFail($carga_id);
        $informe = Informe::where('carga_datos_id', $carga_id)->latest()->first();

        if (!$informe) {
            return redirect()->route('cargas.index')->with('error', 'Primero genera el PDF');
        }

        return view('envios.formulario', compact('carga', 'informe'));
    }

    public function enviar(Request $request, $carga_id)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $informe = Informe::where('carga_datos_id', $carga_id)->latest()->first();
        $carga = \App\Models\CargaDatos::findOrFail($carga_id);

        if (!$informe) {
            return back()->with('error', 'No hay informe generado');
        }

        $rutaPdf = storage_path('app/public/' . $informe->ruta_pdf);
        $validos = $carga->validos;
        $errores = $carga->errores;

        /** @var \Illuminate\Contracts\Mail\Mailable $mailable */
        $mailable = new InformeEnviado($rutaPdf, $validos, count($errores));

        Mail::to($request->email)->send($mailable);

        $informe->update([
            'enviado_por_correo' => true,
            'destinatario_email' => $request->email
        ]);

        return redirect()->route('cargas.index')
            ->with('success', 'Informe enviado por correo a ' . $request->email);
    }
}
