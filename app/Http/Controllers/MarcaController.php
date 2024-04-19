<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    private $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $marcaRepository = new MarcaRepository($this->marca);
        if ($request->has('atributos_modelos')) {
            $atributosModelos = 'modelos:id,' . $request->atributos_modelos;
            $marcaRepository->selecionarAtributosRegistrosRelacionados($atributosModelos);
        } else {
            $marcaRepository->selecionarAtributosRegistrosRelacionados('modelos');
        }
        if ($request->has('filtro')) {
            $marcaRepository->filtrar($request->filtro);
        }
        if ($request->has('atributos')) {
            $marcaRepository->selecionarAtributos($request->atributos);
        } 
        return response()->json($marcaRepository->obterResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());
        $imagem = $request->file('imagem');
        $imagemUrn = $imagem->store('imagens/marcas', 'public');
        $marca = $this->marca->create([
            'nome'      => $request->nome,
            'imagem'    => $imagemUrn
        ]);
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $marca = $this->marca->with('modelos')->find($id);
        if ($marca === null) {
            return  response()->json(["error" => "O recurso pesquisado não existe."], 404);
        }
        return response()->json($marca, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(["error" => "Não foi possível realizar atualização. O recurso solicitado não existe."], 404);
        }
        if ($request->getMethod() === 'PATCH') {
            $regrasDinamicas = [];
            //percorrendo todas as regras definidas no Model
            foreach ($marca->rules() as $input => $regra) {
                //coletando apenas as regras aplicáveis aos parâmetros parciais da requisição
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }
        $imagem = $request->file('imagem');
        $imagemUrn = $imagem->store('imagens/marcas', 'public');
        $marca->fill($request->all());
        $marca->imagem = $imagemUrn;
        $marca->save();
        return response()->json($marca, 200); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(["error" => "O recurso pesquisado não existe"], 404);
        }
        Storage::disk('public')->delete($marca->imagem);
        $marca->delete();
        return response()->json(["success" => "O modelo foi removido com sucesso."], 200);
    }
}
