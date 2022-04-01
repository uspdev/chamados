@props([
    'name' => '',
    'prepend' => '',
    'label' => '',
    'class' => '',
    'value' => '',
    'helpView' => '',
    'rows' => 3,
    'autoLine' => 1,
    'id' => mt_rand(1000000, 1999999),
])

{{-- 
  23/2/2022 - Em uso no config e na criacao de chamados 
  opcional autoLine para "crescer" conforme digita. Default ativado
  --}}

<div class="form-group {{ $class }}">
  @if ($label)
    <label for="{{ $id }}">{!! $label !!}</label>
  @endif
  @includeWhen($helpView,$helpView)
  <div class="input-group">
    @if ($prepend)
      <div class="input-group-prepend">
        <div class="input-group-text">{{ $prepend }}</div>
      </div>
    @endif
    <textarea class="form-control {{ $autoLine ? 'textarea-auto-line': '' }}" 
      rows="{{ $rows }}" id="{{ $id }}" 
      name="{{ $name }}"
      {{ $attributes }}>{{ old($name, $value) }}</textarea>
  </div>
  @error($name)
    <div class="small text-danger">{{ $message }}</div>
  @enderror
</div>

@once
  @section('javascripts_bottom')
    @parent
    <script>
      $(document).ready(function() {

        // aumenta/diminui o tamanho do textarea enquanto digita
        // https://stackoverflow.com/questions/454202/creating-a-textarea-with-auto-resize

        $('.textarea-auto-line').each(function() {
          this.setAttribute("style", "height:" + (this.scrollHeight) + "px;overflow-y:hidden;");
        }).on("input", function() {
          this.style.height = "auto"
          this.style.height = (this.scrollHeight) + "px"
        })
      })
    </script>
  @endsection
@endonce
