@extends('layouts.admin')
@section('title', 'Editar transferência')
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Editar transferência',
                'buttonTitle' => 'Voltar a home',
                'route' => 'admin.home',
                'icon' => 'fas fa-home'
            ])

            <form class="mt-3 px-2" action="{{ route('admin.financial.transfers.update', [ 'id' => $transfer->id ]) }}" method="post">
                @method('PUT')
                @include('components.forms.form_transfer')
                <div class="form-group">
                    <button class="btn btn-success font-weight-bold mt-1 save-button" type="submit">
                        <i class="fas fa-arrow-right"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/select2-bootstrap4.css') }}" />
@endpush

@push('scripts')
<script src="{{ asset('/js/receives/create-edit-receive.js') }}"></script>
@endpush
