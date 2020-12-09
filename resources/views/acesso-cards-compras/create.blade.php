@extends('layouts.admin')
@section('title', 'Novo acessocard compras')
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Novo cartão acesso compras',
                'buttonTitle' => 'Voltar',
                'route' => url()->previous(),
                'icon' => 'fas fa-undo'
            ])

            @include('components.message')

            <form class="mt-3 px-2" action="{{ route('admin.register.acesso-cards-shopping.store', ['pedido_id' => \Request::get('pedido_id')]) }}" enctype="multipart/form-data" method="post">
                @csrf
                @include('components.forms.form_acesso_card_compras')
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
