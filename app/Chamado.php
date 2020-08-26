<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chamado extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function comentarios()
    {
        return $this->hasMany('App\Comentario');
    }

    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }

    public static function atendentes(){
        return [
            [5385361,'Thiago Gomes Veríssimo'],
            [2517070,'Augusto Cesar Freire Santiago'],
            [3426504,'Ricardo Fontoura'],
            [3426511,'José Roberto Visconde de Souza'],
            [2479057,'Neli Maximino'],
            [2807855,'Gilberto Vargas'],
            [7098274,'Paulo Henrique de Araújo'],
            [4988966,'Lenin Oliveira de Araújo'],
            [2431867,'Marco Antonio Rocha'],
            [2989060,'Normando Peres Silva Moura'],
            [4780673,'Wellington da Silva Moura'],
            [9827360,'Ana Claudia Oze Ferraz'],
            [10703080,'Marcos Filipe Ratte Claro']
        ];
    }

    public static function predios(){
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

    public static function complexidades(){
        return ['baixa', 'média','alta'];
    }
}

