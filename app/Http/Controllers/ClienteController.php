<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public Cliente $cliente;

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        if ($request->has('filtro')) {
            $clienteRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $clienteRepository->selectAtributos($request->atributos);
        }

        return response()->json($clienteRepository->getResultado(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->cliente->rules());

        $cliente = $this->cliente->create($request->all());

        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }

        return response()->json($cliente, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente === null) {
            return response()->json(['erro' => 'Impossível realizar update. Recurso solicitado não existe'], 404);
        }

        if ($request->method() === 'PATCH') {

            $regrasDinamicas = [];

            // Percorrendo todas as regras definidas no Model
            foreach ($cliente->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros parciais da requisicao PATCH
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else if ($request->method() === 'PUT') {
            $request->validate($cliente->rules());
        }

        $cliente->fill($request->all());
        $cliente->save();

        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);
        if ($cliente === null) {
            return response()->json(['erro' => 'Impossível realizar exclusão. Recurso solicitado não existe'], 404);
        }

        $cliente->delete();
        return response()->json(['msg' => 'Cliente removido com sucesso!'], 200);
    }
}
