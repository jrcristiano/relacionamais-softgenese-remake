@extends('layouts.admin')
@section('title', 'Editar cliente')
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Editar cliente',
                'buttonTitle' => 'Voltar a home',
                'route' => 'admin.home',
                'icon' => 'fas fa-home'
            ])

            <form class="mt-3 px-2" action="{{ route('admin.register.clients.update', [ 'id' => $client->id ]) }}" method="post">
                @include('components.forms.form_client')
                @method('PUT')
                <div class="form-group">
                    <button class="btn btn-success save-button" type="submit">
                            <i class="fas fa-arrow-right"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('/js/jquery.mask.js') }}"></script>
    <script src="{{ asset('/js/clients/create-edit-client.js') }}"></script>
@endpush
