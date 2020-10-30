@extends('master')

@section('content')
@parent
@if($setor != null)

@include('setores.partials.setor')
@include('setores.partials.recursivo')

@include('common.adicionar-pessoas-modal', ['modal' =>$modal_pessoa])

@include('setores.partials.modal')
@else
Sem setor
@endif

@endsection

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        // se houver anchor na url, vamos abrir os detalhes 
        if (location.hash) {
            $('#responsavel_' + location.hash.substring(1)).collapse('show')
            console.log('abrindo #responsavel_' + window.location.hash.substring(1))
        }

        $("[data-collapse-group='myDivs']").click(function() {
            var $this = $(this);
            $("[data-collapse-group='myDivs']:not([data-target='" + $this.data("target") + "'])").each(function() {
                $($(this).data("target")).removeClass("in").addClass('collapse');
            });
        });
    })

</script>
@endsection
