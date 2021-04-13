<?php

namespace App\Http\Controllers;

use App\Mail\SolicitudAmigo;
use App\Models\AmigosModels;
use App\Models\ComentariosModels;
use App\Models\PublicacionesModels;
use App\Models\UsariosModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AmigosController extends Controller
{
    public function addAmigoCorreo($idusuario)
    {
        $amigo = AmigosModels::where('para', $idusuario)->first();

        if ($amigo)
            return json_encode(['estatus' => 'error', 'mensaje' => 'Ya has enviado la solicitud']);

        $amigo = new AmigosModels();
        $amigo->de = session('usuario')->id;
        $amigo->para = $idusuario;
        $amigo->estatus = 0;
        $amigo->save();

        $usuario = AmigosModels::select('usuarios.nombre as de', 'amigos.id')
            ->join('usuarios', 'de', '=', 'usuarios.id')
            ->where('amigos.de', session('usuario')->id)
            ->first();

        $correo_para = UsariosModels::find($idusuario);

        $correo = new SolicitudAmigo($usuario);

        Mail::to($correo_para->correo)->send($correo);

        return json_encode(['estatus' => 'success', 'mensaje' => 'Solicitud enviada']);
    }

    public function aceptarSolicitudes($idsolicitud)
    {
        $amigo = AmigosModels::where('id', $idsolicitud)->where('estatus', '1')->first();

        if ($amigo){
            echo "<h1>Ya respondiste y ahora son amigos, puedes cerrar esya ventana</h1>";
            return false;
        }

        $amigo = AmigosModels::find($idsolicitud);
        $amigo->estatus = 1;
        $amigo->save();

        echo "<h1>Ya puedes cerrar esta ventana</h1>";
    }

    public function verPerfiles($idUsuario)
    {
        if($idUsuario == session('usuario')->id)
            return redirect()->route('perfil');

        $usuarioPerfil = UsariosModels::where('id', $idUsuario)->first();

        $amigos = AmigosModels::where('de', session('usuario')->id)
            ->Where('para', $idUsuario)
            ->orWhere('para', session('usuario')->id)
            ->first();

        if (!$amigos)
            return view('Verperfilesdusuarios', ['estatus' => 'error', 'mensaje' => 'No puedes ver su informacion hasta que sean amigos']);

        if ($amigos->estatus != 1)
            return view('Verperfilesdusuarios', ['estatus' => 'error', 'mensaje' => 'No puedes ver su informacion hasta que sean amigos']);

        $comentarios = ComentariosModels::all();

        $publicaciones = PublicacionesModels::where('usuarioId', $idUsuario)->get();

        return view('verPerfilesdusuarios', ['usuarioPerfil' => $usuarioPerfil, 'publicaciones' => $publicaciones, 'comentarios' => $comentarios]);
    }
}
