@extends('layouts.admin')
@section('title', 'Editar planilha')
@section('content')
<div class="container-fluid">
        <div class="row shadow bg-white rounded">
            @include('components.leftbar')

            <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Editar planilha',
                'buttonTitle' => 'Voltar',
                'route' => url()->previous(),
                'icon' => 'fas fa-undo'
            ])

            <form class="mt-3" action="{{ route('admin.register.spreadsheets.update', ['id' => $spreadsheet->id, 'premiado_id' => $awardId]) }}" method="post">
                @method('PUT')
                @include('components.forms.form_spreadsheet')
                <div class="form-group">
                    <button class="btn btn-success" type="submit">
                        <i class="fas fa-arrow-right"></i> Editar
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
