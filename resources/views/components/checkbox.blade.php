@props([
    'name' => '',
    'prepend' => '',
    'label' => '',
    'class' => '',
    'value' => 1,
    'checked' => null,
    'helpView' => '',
    'id' => mt_rand(1000000, 1999999),
])

<div class="form-check {{ $class }}">
  <input class="form-check-input" type="checkbox" 
    name="{{ $name }}"
    id="{{ $id }}" 
    value="{{ $value }}"
    @if($checked) checked @endif
    {{ $attributes }}
  />
  <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
</div>