@extends('layouts.admin')
@section('title', 'Editar cliente')
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Editar banco',
                'buttonTitle' => 'Voltar',
                'route' => url()->previous(),
                'icon' => 'fas fa-undo'
            ])

            <form class="mt-3 px-2" action="{{ route('admin.register.banks.update', [ 'id' => $bank->id ]) }}" method="post">
                @include('components.forms.form_bank')
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
    <script src="{{ asset('/js/banks/create-edit-bank.js') }}"></script>
@endpush
