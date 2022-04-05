@props([
    'name' => '',
    'prepend' => '',
    'label' => '',
    'value' => '',
    'options' => [],
    'class' => '',
    'helpView' => '',
    'id' => mt_rand(1000000, 1999999),
])

{{-- 
  pode ser passado um item somente ou um array com itens
--}}

@forelse ($options as $option)
  <div class="form-check {{ $class }}">
    <input class="form-check-input" type="radio"
      name="{{ $name }}"
      id="{{ $id + $loop->index}}"
      value="{{ $option['value'] }}"
      @if($value == $option['value']) checked @endif
      {{ $attributes }}
    />
    <label class="form-check-label" for="{{ $id  + $loop->index}}">{{ $option['label'] }}</label>
  </div>
@empty
  {{-- não testado esse trecho --}}
  <div class="form-check {{ $class }}">
    <input class="form-check-input" type="radio" 
      name="{{ $name }}"
      id="{{ $id }}" 
      value="{{ $value }}"
      @if($value == $option['value']) checked @endif
      {{ $attributes }}
    />
    <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
  </div>
@endforelse