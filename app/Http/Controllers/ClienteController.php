<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    private $cliente;

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $clienteRepository = new ClienteRepository($this->cliente);
     
        if ($request->has('filtro')) {
            $clienteRepository->filtrar($request->filtro);
        }
        if ($request->has('atributos')) {
            $clienteRepository->selecionarAtributos($request->atributos);
        } 
        return response()->json($clienteRepository->obterResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate($this->cliente->rules());
        $cliente = $this->cliente->create([
            'nome'      => $request->nome
        ]);
        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $cliente = $this->cliente->find($id);
        if ($cliente === null) {
            return  response()->json(["error" => "O recurso pesquisado não existe."], 404);
        }
        return response()->json($cliente, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $cliente = $this->cliente->find($id);
        if ($cliente === null) {
            return response()->json(["error" => "Não foi possível realizar atualização. O recurso solicitado não existe."], 404);
        }
        if ($request->getMethod() === 'PATCH') {
            $regrasDinamicas = [];
            foreach ($cliente->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($cliente->rules());
        }
        $cliente->fill($request->all());
        $cliente->save();
        return response()->json($cliente, 200); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $cliente = $this->cliente->find($id);
        if ($cliente === null) {
            return response()->json(["error" => "O recurso pesquisado não existe"], 404);
        }
        $cliente->delete();
        return response()->json(["success" => "O cliente foi removido com sucesso."], 200);
    }
}
