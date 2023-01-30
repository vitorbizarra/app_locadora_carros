<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public Marca $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);

        if ($request->has('atributos_modelos')) {
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos:id,' . $request->atributos_modelos);
        } else {
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if ($request->has('filtro')) {
            $marcaRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $marcaRepository->selectAtributos($request->atributos);
        }

        return response()->json($marcaRepository->getResultadoPaginado(3), 200);
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
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $imagem = $request->file('imagem');

        $imagem_urn = $imagem->store('imagens/marcas', 'public');

        $marca = $this->marca->create([
            'nome'   => $request->nome,
            'imagem' => $imagem_urn
        ]);
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }

        return response()->json($marca, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar update. Recurso solicitado não existe'], 404);
        }

        if ($request->method() === 'PATCH') {

            $regrasDinamicas = [];

            // Percorrendo todas as regras definidas no Model
            foreach ($marca->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros parciais da requisicao PATCH
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());
        } else if ($request->method() === 'PUT') {
            $request->validate($marca->rules(), $marca->feedback());
        }

        $marca->fill($request->all());
        if ($request->file('imagem')) {
            // Remove o arquivo antigo caso um novo arquivo tenha sido enviado no $request
            Storage::disk('public')->delete($marca->imagem);
            // Captura e realiza o upload da nova imagem enviada no request
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens/marcas', 'public');
            // Atribui o nome da nova imagem à instância de Modelo
            $marca->imagem = $imagem_urn;
        }
        $marca->save();

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar exclusão. Recurso solicitado não existe'], 404);
        }

        // Remove o arquivo antigo caso um novo arquivo tenha sido enviado no $request
        if ($marca->imagem) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $marca->delete();
        return response()->json(['msg' => 'Marca removida com sucesso!'], 200);
    }
}
