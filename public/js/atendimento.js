$(function () {
    $(".delete-item").on("click", function(){
        return confirm("Tem certeza que deseja deletar?");
    });

    $('[data-toggle="tooltip"]').tooltip({
        container: 'body',
        html: true
    });
});
