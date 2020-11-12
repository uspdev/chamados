$(document).ready(function(){
    //ativa os tooltips do bootstrap
    $('[data-toggle="tooltip"]').tooltip();
    $("#input_arquivo").change(function(){
        var file_name = $(this).val().split(/(\\|\/)/g).pop();
        if(file_name.length == 0){
            $("#nome_arquivo").fadeOut();
            $("#nome_arquivo p").text('');
            return;
        }else{
            var files = $("#input_arquivo")[0].files;
            
            for (var i = 0; i < files.length; i++)
            {
                file_name = files[i].name;
                $("#nome_arquivo ul").append(
                    '<li title="('+(files[i].size/1024).toFixed(2)+'KB)"><span id="'+i+'" class="btn text-danger btn-sm"> <i class="fas fa-times"></i></span>'+file_name+'</li>');
                
                if(files[i].size/1024/1024 > 2){
                    $("#submit_form_arquivo").addClass('disabled');
                    $("#"+i).parent().addClass('disabled');
                    $("#"+i).parent().attr('data-toggle','tooltip'); 
                    $("#"+i).parent().attr('title','O arquivo ultrapassa o tamanho máximo permitido de 2MB'); 
                     
                }
            }
            $("#nome_arquivo").fadeIn();
            $("#nome_arquivo ul li span").click(remove);
           
            
        }
       
        
    });

    
    
    $("#limpar_input_arquivo").click(function(){
        $("#input_arquivo").val('');
        $("#nome_arquivo").fadeOut();
        $("#nome_arquivo ul").text('');
        $("#input_arquivo")[0].files = new FileListItems([]);
        $("#submit_form_arquivo").removeClass('disabled');
        
    });
    $("#submit_form_arquivo").click(function(){
        $("#form_arquivo").submit();
    });
    $(".btn-editar.btn-arquivo-acao, .limpar-edicao-nome").click(function(){
        $(this).parent().parent().parent().toggleClass('modo-edicao');
        $(this).parent().parent().parent().toggleClass('modo-visualizacao');
    });

});

function remove(){
    var index = $(this).attr('id');
    var files = Array.from($("#input_arquivo")[0].files);
    files.splice(index,1);
    var fileList = new FileListItems(files);
    $("#input_arquivo")[0].files = fileList;
    $(this).parent().remove();
    $("#submit_form_arquivo").removeClass('disabled');
    for (var i = 0; i < fileList.length; i++)
    {
        if(files[i].size/1024/1024 > 2){
            $("#submit_form_arquivo").addClass('disabled');
        }
    }
    $('.nome-arquivo .preview-files li span').each(function( index ) {
        $(this).attr('id', index);
    });
}

function FileListItems (files) {
    var b = new ClipboardEvent("").clipboardData || new DataTransfer()
    for (var i = 0, len = files.length; i<len; i++) b.items.add(files[i])
    return b.files
}


function ativar_exclusao(){
    $(".deletar-imagem-btn").click(function(){
        var arquivo_id = $(this).attr('data-id');
        if(confirm("Tem certeza que deseja remover a imagem?")){
            $('.deletar-imagem-'+arquivo_id).submit();
        }
    });
}