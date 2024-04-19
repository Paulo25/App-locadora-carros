<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractRepository
{
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selecionarAtributosRegistrosRelacionados(string $atributos): void
    {
        $this->model = $this->model->with($atributos);
    }

    public function filtrar(string $filtro): void
    {
        $filtros = explode(';', $filtro);
        foreach ($filtros as $key => $condicao) {
            $parametros = explode(':', $condicao);
            $this->model = $this->model->where(
                $parametros[0],
                $parametros[1],
                $parametros[2]
            );
        }
    }

    public function selecionarAtributos(string $atributos): void
    {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function obterResultado(): Collection
    {
        return $this->model->get();
    }
}
