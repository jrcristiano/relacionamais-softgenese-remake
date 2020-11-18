@extends('layouts.admin')
@section('title', 'Novo chamado')
@section('content')
@php
    // dd($baseAcessoCardsCompletos);
@endphp
<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="sgi-content-title">Editar chamado</h2>

                @php
                    $pedidoId = $id ?? null;
                @endphp

                    <form class="ml-auto mt-2 mr-1" action="{{ route('admin.operational.base-acesso-card-duplicate.update', ['proxy' => $callCenter->acesso_card_proxy ]) }}" method="post">
                        @if ($callCenter->base_acesso_card_status == 1 && $callCenter->call_center_reason != 5)
                            @csrf
                            @method('PUT')
                            <button class="btn btn-danger font-weight-bold" type="submit">
                                <i class="far fa-credit-card"></i> Gerar 2º via
                            </button>
                        @endif
                    </form>
                    <form class="mt-2 mr-1" action="{{ route('admin.operational.base-acesso-card-completo.update', ['proxy' => $callCenter->acesso_card_proxy ]) }}" method="post">
                        @if ($callCenter->base_acesso_card_status == 1)
                            @csrf
                            @method('PUT')
                            <button class="btn btn-danger font-weight-bold" type="submit">
                                <i class="fas fa-power-off"></i> Cancelar cartão
                            </button>
                        @endif
                    </form>

                <a class="btn btn-primary sgi-btn-bold mt-2" href="{{ route('admin.operational.acesso-cards-completo.show', ['document' => \Request::get('document')]) }}">
                    <i class="fas fa-eye"></i> Consultar premiado
                </a>
                <a class="btn btn-primary sgi-btn-bold ml-1 mt-2" href="{{ url()->previous() }}">
                    <i class="fas fa-undo"></i> Voltar
                </a>
            </header>

            @include('components.message')

            <form class="mt-3 px-2" action="{{ route('admin.operational.call-center.update', [
                'tipo_cartao' => 'completo',
                'premiado' => \Request::get('premiado'),
                'document' => \Request::get('document'),
                'id' => $callCenter->id
            ]) }}" method="post">

                <div class="form-group">
                    @csrf
                    @method('PUT')
                    @include('components.forms.form_call_center')
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
    <script src="{{ asset('/js/call-center/create-edit-call-center.js') }}"></script>
@endpush
