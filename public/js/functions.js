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

/* @autor uspdev/alecosta 10/02/2022
* Função que verifica se o tipo de campo adicionado na fila é caixa de seleção
* Se for, muda tipo tipo de campo valor de input para textarea
*/
function mudarCampoInputTextarea(campo) {
	var fieldTypeSelect = $('select[name="' + campo + '"]').find(":selected").val();
  // se é caixa de seleção, muda o campo valor para textarea
  if (fieldTypeSelect == 'select') {
    $('input[name="' + campo.replace('][type]', '][value]') + '"]').each(function () {
      var classe = $(this).attr('class');
      var style = $(this).attr('style');
      var name = $(this).attr('name');
      var value = $(this).val();
      var textbox = $(document.createElement('textarea'));
      textbox.attr('class', classe);
      textbox.attr('name', name);
      textbox.attr('style', style);
      textbox.val(value);
      $(this).replaceWith(textbox);
    });
	// do contrário, volta o campo valor para input
  } else {
      $('textarea[name="' + campo.replace('][type]', '][value]') + '"]').each(function () {
      var classe = $(this).attr('class');
      var style = $(this).attr('style');
      var name = $(this).attr('name');
      var value = $(this).val();
      var inputbox = $(document.createElement('input'));
      inputbox.attr('class', classe);
      inputbox.attr('name', name);
      inputbox.attr('style', style);
      inputbox.val(value);
      $(this).replaceWith(inputbox);
    });
  }
}
