<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    private $carro;

    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $carroRepository = new CarroRepository($this->carro);
        if ($request->has('atributos_modelo')) {
            $atributosCarro = 'modelo:id,' . $request->atributos_modelo;
            $carroRepository->selecionarAtributosRegistrosRelacionados($atributosCarro);
        } else {
            $carroRepository->selecionarAtributosRegistrosRelacionados('modelo');
        }
        if ($request->has('filtro')) {
            $carroRepository->filtrar($request->filtro);
        }
        if ($request->has('atributos')) {
            $carroRepository->selecionarAtributos($request->atributos);
        } 
        return response()->json($carroRepository->obterResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate($this->carro->rules());
        $carro = $this->carro->create([
            'modelo_id'     => $request->modelo_id,
            'placa'         => $request->placa,
            'disponivel'    => $request->disponivel,
            'km'            => $request->km
        ]);
        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $carro = $this->carro->with('modelo')->find($id);
        if ($carro === null) {
            return response()->json(["error" => "O recurso pesquisado não existe"], 404);
        }
        return response()->json($carro, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $carro = $this->carro->find($id);
        if ($carro === null) {
            return response()->json(["error" => "Não foi possível realizar atualização. O recurso solicitado não existe."], 404);
        }
        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            foreach ($carro->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($carro->rules());
        }
        $carro->fill($request->all());
        $carro->update();
        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $carro = $this->carro->find($id);
        if ($carro === null) {
            return response()->json(["error" => "O recurso pesquisado não existe"], 404);
        }
        $carro->delete();
        return response()->json(["success" => "O carro foi removido com sucesso."], 200);
    }
}
