<?php

namespace App\Http\Controllers;

use App\Mail\SendPost;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::get();

        $data = $clientes->map(function($cliente) {
            return [
                'nombre' => $cliente->nombre,
                'apellido' => $cliente->apellido,
                'correo' => $cliente->correo,
                'mensajeCorreo' => $cliente->mensajeCorreo
            ];
        });

        return response()->json([
            'mensaje' => 'Listado de clientes',
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'max:255'],
            'apellido' => ['required', 'max:255'],
            'correo' => ['required', 'max:255'],
            'mensajeCorreo' => ['required','max:255']
        ]);

        $cliente = Cliente::create([
            'nombre' => $request['nombre'],
            'apellido' => $request['apellido'],
            'correo' => $request['correo'],
            'mensajeCorreo' => $request['mensajeCorreo']
        ]);

        //Mail
        $details = [
            'nombre' => $request['nombre'],
            'apellido' => $request['apellido'],
            'correo' => $request['correo'],
            'mensajeCorreo' => $request['mensajeCorreo'],
        ];

        Mail::to('boxerborrego87@gmail.com')->send(new SendPost($details));
        //fin Mail

        return response()->json([
            'mensaje' => 'El cliente se registro correctamente',
            'cliente' => $cliente
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'nombre' => ['required','max:255'],
            'apellido' => ['required','max:255'],
            'mensajeCorreo' => ['required', 'max:255']
        ]);

        $cliente = Cliente::findOrFail($id);

        $cliente->nombre = $request['nombre'];
        $cliente->apellido = $request['apellido'];
        $cliente->mensajeCorreo = $request['mensajeCorreo'];
        $cliente->save();

        return response()->json([
            'mensaje' => 'Se actualizó correctamente el cliente',
            'cliente' => $cliente
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->json([
            'mensaje'=> 'Se elimina el cliente elegido'
        ]);
    }
}
