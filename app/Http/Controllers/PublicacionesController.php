<?php

namespace App\Http\Controllers;

use App\Models\PublicacionesModels;
use Illuminate\Http\Request;

class PublicacionesController extends Controller
{
    public function publicaciones(Request $datos)
    {
        $publicacion = new PublicacionesModels();

        $datos->validate([
            'imagen' => 'image',
        ]);

        if ($datos->hasFile('imagen')){

            $publicacion->imagen = $datos->file('imagen')->store('public');
        }


        if ($datos->texto) {
            $publicacion->texto = $datos->texto;
        }

        $publicacion->usuarioId = session('usuario')->id;
        $publicacion->save();

        return redirect()->route('home');

    }

    public function likes($idpost)
    {
        $publicacion = PublicacionesModels::find($idpost);

        $publicacion->likes = $publicacion->likes + 1;

        $verificar = $publicacion->save();

        if($verificar)
            return json_encode(['estatus' => 'success' ,'mensaje' => 'Ya le diste like a la publicacion']);
        else
            return json_encode(['estatus' => 'error' ,'mensaje' => 'Hubo un error']);
    }

}
