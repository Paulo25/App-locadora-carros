<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    private object $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marca = $this->marca->all();
        return response()->json($marca, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());
        $imagem = $request->file('imagem');
        $imagemUrn = $imagem->store('imagens/marcas', 'public');
        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagemUrn
        ]);
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return  response()->json(["error" => "O recurso pesquisado não existe."], 404);
        }
        return $marca;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
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
        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
        }
        $imagem = $request->file('imagem');
        $imagemUrn = $imagem->store('imagens/marcas', 'public');
        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagemUrn
        ]);
        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        $response = [];
        Storage::disk('public')->delete($marca->imagem);
        if ($marca && $marca->delete()) {
            $response = response()->json(["success" => "A marca foi removida com sucesso."], 200);
        } else {
            $response = response()->json(["error" => "O recurso não exite."], 404);
        }
        return $response;
    }
}
