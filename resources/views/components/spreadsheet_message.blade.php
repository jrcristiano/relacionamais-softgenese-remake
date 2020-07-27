@php
    $message_name = $message_name ?? 'error';
    $message_icon = $message_name == 'message' ? '<i class="fas fa-check"></i>' : '<i class="fas fa-close"></i>';
    $message_class = $message_name == 'message' ? 'success' : 'danger';
@endphp
@if(\Session::has($message_name))
    <div class="alert alert-{{ $message_class }} alert-dismissible fade show m-2 p-3" role="alert">
        {!! session($message_name) !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
