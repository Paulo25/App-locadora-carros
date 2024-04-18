<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    protected object $modelo;

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->modelo->with('marca')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->Rules());
        $imagem = $request->file('imagem');
        $imagemUrn = $imagem->store('imagens/modelos', 'public');
        $modelo = $this->modelo->create([
            'marca_id'      => $request->marca_id,
            'nome'          => $request->nome,
            'imagem'        => $imagemUrn,
            'numero_portas' => $request->numero_portas,
            'lugares'       => $request->lugares,
            'air_bag'       => $request->air_bag,
            'abs'           => $request->abs
        ]);
        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if ($modelo === null) {
            return response()->json(["error" => "O recurso pesquisado não existe"], 404);
        }
        return response()->json($modelo, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(["error" => "Não foi possível realizar atualização. O recurso solicitado não existe."], 404);
        }
        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            foreach ($modelo->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($modelo->rules());
        }
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
        }
        $imagem = $request->file('imagem');
        $imagemUrn = $imagem->store('imagens/modelos', 'public');
        $modelo->fill($request->all());
        $modelo->imagem = $imagemUrn;
        $modelo->update();
        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(["error" => "O recurso pesquisado não existe"], 404);
        }
        Storage::disk('public')->delete($modelo->imagem);
        $modelo->delete();
        return response()->json(["success" => "A marca foi removida com sucesso."], 200);
    }
}
