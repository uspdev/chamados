/* @autor uspdev/alecosta 10/02/2022
* Função que ordena de form alfabética as opções de um campo caixa de seleção adicionado a fila
*/
function ordenarOpcoes(campo) {
    // Get all options from select field
    var options = $('select[name="extras[' + campo + ']"] option');
    var arr = options.map(function(_, o) {
        return {
        t: $(o).text(),
        v: o.value,
        s: $(o).attr('selected')
        };
    }).get();
    // Sort alphabetic order
    arr.sort(function(o1, o2) {
        return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0;
    });
    options.each(function(i, o) {
        console.log(i);
        o.value = arr[i].v;
        $(o).text(arr[i].t);
        $(o).attr('selected', arr[i].s);
    });
    // Set to first option Selecione... ou Escolha um..., onde value="" e selected
    var valFirstOption = options.first().val();
    $('select[name="extras[' + campo + ']"] option[value=""]').insertBefore('select[name="extras[' + campo + ']"] option[value="' + valFirstOption + '"]');
}