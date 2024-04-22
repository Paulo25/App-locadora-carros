<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    private $locacao;

    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        if ($request->has('filtro')) {
            $locacaoRepository->filtrar($request->filtro);
        }
        if ($request->has('atributos')) {
            $locacaoRepository->selecionarAtributos($request->atributos);
        }
        return response()->json($locacaoRepository->obterResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate($this->locacao->rules());
        $locacao = $this->locacao->create([
            'cliente_id'                    => $request->cliente_id,
            'carro_id'                      => $request->carro_id,
            'data_inicio_periodo'           => $request->data_inicio_periodo,
            'data_final_previsto_periodo'   => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo'  => $request->data_final_realizado_periodo,
            'valor_diaria'                  => $request->valor_diaria,
            'km_inicial'                    => $request->km_inicial,
            'km_final'                      => $request->km_final
        ]);
        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(["error" => "O recurso pesquisado não existe"], 404);
        }
        return response()->json($locacao, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(["error" => "Não foi possível realizar atualização. O recurso solicitado não existe."], 404);
        }
        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            foreach ($locacao->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($locacao->rules());
        }
        $locacao->fill($request->all());
        $locacao->update();
        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(["error" => "O recurso pesquisado não existe"], 404);
        }
        $locacao->delete();
        return response()->json(["success" => "A locação foi removida com sucesso."], 200);
    }
}
