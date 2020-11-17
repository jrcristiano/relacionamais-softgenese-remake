@extends('layouts.admin')
@section('title', 'Editar fornecedor')
@section('content')
<div class="container-fluid">
        <div class="row shadow bg-white rounded">
            @include('components.leftbar')

            <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Editar fornecedor',
                'buttonTitle' => 'Voltar',
                'route' => url()->previous(),
                'icon' => 'fas fa-undo'
            ])

            <form class="mt-3" action="{{ route('admin.register.providers.update', ['id' => $provider->id]) }}" method="post">
                @method('PUT')
                @include('components.forms.form_provider')
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
    <script src="{{ asset('/js/providers/create-edit-provider.js') }}"></script>
@endpush
