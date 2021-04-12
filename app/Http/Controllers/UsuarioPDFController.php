<?php

namespace App\Http\Controllers;

use App\Models\ComentariosModels;
use App\Models\PublicacionesModels;
use App\Models\UsariosModels;
use Illuminate\Http\Request;
use PDF;

class UsuarioPDFController extends Controller
{
    public function descargarInformacion()
    {
        $usuario = UsariosModels::where('id', session('usuario')->id)->first();

        $comentarios = ComentariosModels::where('usuarioId', $usuario->id)->get()->count();

        $publicaciones = PublicacionesModels::where('usuarioId', $usuario->id)->get()->count();

        $pdf = \PDF::loadView('datos', ['usuario' => $usuario, 'comentarios' => $comentarios, 'publicaciones' => $publicaciones]);

        return $pdf->download($usuario->nombre.$usuario->apellido_paterno.$usuario->apellido_materno.'.pdf');
    }
}
