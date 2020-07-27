@extends('layouts.admin')
@section('title', 'Novo fornecedor')
@section('content')
<div class="container-fluid">
        <div class="row shadow bg-white rounded">
            @include('components.leftbar')
            <div class="col-lg-10 sgi-container shadow-sm rounded">
                @include('components.header_content', [
                    'title' => 'Novo fornecedor',
                    'buttonTitle' => 'Voltar a home',
                    'route' => 'admin.home',
                    'icon' => 'fas fa-home'
                ])

            <form class="mt-3" action="{{ route('admin.register.providers.store') }}" method="post">
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
