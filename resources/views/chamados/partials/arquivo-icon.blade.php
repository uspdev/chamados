@if (preg_match('/pdf/i', $arquivo->mimeType))
  <i class="fas fa-file-pdf"></i>
@else
  <i class="fas fa-file"></i>
@endif
