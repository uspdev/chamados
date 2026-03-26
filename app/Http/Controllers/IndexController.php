<?php

namespace App\Http\Controllers;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class IndexController extends Controller
{
    public function __construct()
    {
        #$this->middleware('auth')->except(['index','ajuda']);
    }

    public function index()
    {
        return view('index');
    }

    public function ajuda()
    {
        \UspTheme::activeUrl('ajuda');
        return view('ajuda/index');
    }

    public function manual_usuario()
    {
        \UspTheme::activeUrl('ajuda');
        return view('ajuda/manual-usuario');
    }

    public function manual_atendente()
    {
        \UspTheme::activeUrl('ajuda');
        return view('ajuda/manual-atendente');
    }
}
