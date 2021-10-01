@props([
    'name' => '',
    'label' => '.',
    'class' => '',
    'prepend' => '',
    'id' => mt_rand(1000000, 1999999),
])

@if ($prepend)
<div>{{ $prepend }}</div>
@endif
<div class="custom-control custom-switch ml-2 mt-2 {{ $class }}">


    <input type="checkbox" class="custom-control-input" id="{{ $id }}" name="{{ $name }}"
      {{ $attributes }} />
    @if ($label)
      <label class="custom-control-label" for="{{ $id }}">{{ $label }}</label>
    @endif

</div>
