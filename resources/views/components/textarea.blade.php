@props([
    'name' => '',
    'prepend' => '',
    'label' => '',
    'class' => '',
    'value' => '',
    'rows' => 3,
    'id' => mt_rand(1000000, 1999999),
])

<div class="form-group {{ $class }}">
  @if ($label)<label for="{{ $id }}">{!! $label !!}</label>@endif
  <div class="input-group">
    @if ($prepend)
      <div class="input-group-prepend">
        <div class="input-group-text">{{ $prepend }}</div>
      </div>
    @endif
    <textarea
      class="form-control"
      rows="{{ $rows }}"
      id="{{ $id }}"
      name="{{ $name }}"
      {{ $attributes }}
    >{{ old($name, $value) }}</textarea>
  </div>
  @error($name) <div class="small text-danger">{{ $message }}</div> @enderror
</div>
