<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    public Locacao $locacao;

    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        if ($request->has('filtro')) {
            $locacaoRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $locacaoRepository->selectAtributos($request->atributos);
        }

        return response()->json($locacaoRepository->getResultado(), 200);
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
        $request->validate($this->locacao->rules());

        $locacao = $this->locacao->create($request->all());

        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }

        return response()->json($locacao, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Locacao $locacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null) {
            return response()->json(['erro' => 'Impossível realizar update. Recurso solicitado não existe'], 404);
        }

        if ($request->method() === 'PATCH') {

            $regrasDinamicas = [];

            // Percorrendo todas as regras definidas no Model
            foreach ($locacao->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros parciais da requisicao PATCH
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else if ($request->method() === 'PUT') {
            $request->validate($locacao->rules());
        }

        $locacao->fill($request->all());
        $locacao->save();

        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(['erro' => 'Impossível realizar exclusão. Recurso solicitado não existe'], 404);
        }

        $locacao->delete();
        return response()->json(['msg' => 'Locação removida com sucesso!'], 200);
    }
}
