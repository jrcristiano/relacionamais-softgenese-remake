@php
    $messageName = $messageName ?? 'error';
    $messageIcon = $messageName == 'message' ? '<i class="fas fa-check"></i>' : '<i class="fas fa-close"></i>';
    $messageClass = $messageName == 'message' ? 'success' : 'danger';
@endphp
@if(\Session::has($messageName))
    @if (is_array(\Session::get($messageName)))
        @php
            $messages = \Session::get($messageName);
        @endphp

        @foreach ($messages as $key => $message)
            <div class="alert alert-{{ $messageClass }} alert-dismissible fade show m-2 p-3" role="alert">
                <strong>{!! $messageIcon !!} Erro ao enviar arquivo:</strong> documento <b>{{ $message['document'] }}</b>
                    inválido na linha <b>{{ $message['line'] }}</b>. Verifique os tipos de dados, edite e tente novamente.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endforeach
    @else
        <div class="alert alert-{{ $messageClass }} alert-dismissible fade show m-2 p-3" role="alert">
            <strong>{!! $messageIcon !!}</strong> {!! session($messageName) !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endif
