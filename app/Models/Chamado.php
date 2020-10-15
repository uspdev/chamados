<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Comentario;
use App\Models\Fila;

class Chamado extends Model
{
    use HasFactory;
    
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_chamado')
            ->withPivot('funcao')->withTimestamps();
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function fila(){
        return $this->belongsTo(Fila::class);
    }

    public static function predios() {
        return [
            'Administração', 
            'Letras',
            'Filosofia e Ciências Sociais',
            'História e Geografia',
            'Casa de Cultura Japonesa',
            'Favos',
            'Outro'
        ];
    }

    public static function complexidades() {
        return ['baixa', 'média', 'alta'];
    }

    public static function status() {
        return ['Triagem', 'Atribuído', 'Fechado'];
    }
}

