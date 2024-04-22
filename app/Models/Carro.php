<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = ['modelo_id', 'placa', 'disponivel', 'km'];

    public function rules()
    {
        return [
            'modelo_id'     => 'exists:modelos,id',
            'placa'         => 'required|max:10|unique:carros,placa,' . $this->id,
            'disponivel'    => 'required|boolean',
            'km'            => 'required|integer|digits_between:0,999999'
        ];
    }

    //Um carro pertence a um modelo
    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}
