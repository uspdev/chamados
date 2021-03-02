<?php

namespace App\Mail;

trait MailTrait
{
    public $chamado;

    public function construirAssunto()
    {
        $chamado = $this->chamado;
        $fila = $chamado->fila;
        return
        '[' . config('app.name') . ']'
        . ' #'.$chamado->nro . '/' . $chamado->created_at->year . ' | '
        . ' (' . $fila->setor->sigla . ') ' . $fila->nome . ' | '
        . $chamado->assunto;
    }
}
