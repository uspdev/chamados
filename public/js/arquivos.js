$(document).ready(function(){
    //ativa os tooltips do bootstrap
    $('[data-toggle="tooltip"]').tooltip()
    $("#input_arquivo").change(function(){
        var file_name = $(this).val().split(/(\\|\/)/g).pop();
        if(file_name.length == 0){
            $("#nome_arquivo").fadeOut();
            $("#nome_arquivo p").text('');
            return;
        }else{
            $("#nome_arquivo").fadeIn();
            $("#nome_arquivo p").text(file_name);
        }
       
        
    });
    
    $("#limpar_input_arquivo").click(function(){
        $("#input_arquivo").val('');
        $("#nome_arquivo").fadeOut();
        $("#nome_arquivo p").text('');
    });
    $("#submit_form_arquivo").click(function(){
        $("#form_arquivo").submit();
    });
    $(".btn-editar.btn-arquivo-acao, .limpar-edicao-nome").click(function(){
        $(this).parent().parent().parent().toggleClass('modo-edicao');
        $(this).parent().parent().parent().toggleClass('modo-visualizacao');
    });

});


function ativar_exclusao(){
    $(".deletar-imagem-btn").click(function(){
        var arquivo_id = $(this).attr('data-id');
        if(confirm("Tem certeza que deseja remover a imagem?")){
            $('.deletar-imagem-'+arquivo_id).submit();
        }
    });
}