@extends('layouts.admin')
@section('title', "Chamado")
@section('content')
@php
    // dd($callCenter);
@endphp
<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-3 rounded-0"><i class="fas fa-bars"></i></button>
                <h1 class="font-weight-bold text-uppercase sgi-content-title">CHAMADO</h1>
            </header>
            <div class="container-fluid mt-2">
                <div class="row p-2">
                    <label class="font-weight-bold">Nome do premiado</label>
                    <div class="col-md-12 text-uppercase sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $callCenter->acesso_card_name ?? $callCenter->acesso_card_shopping_name }}
                    </div>
                </div>
                <div class="row p-2">
                    <label class="font-weight-bold">Documento</label>
                    <div  class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $callCenter->acesso_card_document ?? $callCenter->acesso_card_shopping_document }}
                    </div>
                </div>
                <div class="row p-2">
                    <label class="font-weight-bold">Produto</label>
                    <div class="col-md-12 text-uppercase sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $callCenter->call_center_subproduct == 1 ? 'ACESSOCARD COMPLETO' : 'ACESSOCARD COMPRAS' }}
                    </div>
                </div>
                <div class="row p-2">
                    <label class="font-weight-bold">Motivo</label>
                    <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        @php
                        if ($callCenter->call_center_reason == 1) {
                            $reason = 'ROUBO';
                        }

                        if ($callCenter->call_center_reason == 2) {
                            $reason = 'FURTO';
                        }

                        if ($callCenter->call_center_reason == 3) {
                            $reason = 'PERDA';
                        }

                        if ($callCenter->call_center_reason == 4) {
                            $reason = 'EXTRAVIO';
                        }

                        if ($callCenter->call_center_reason == 5) {
                            $reason = 'INFORMAÇÃO';
                        }
                    @endphp
                    {{ $reason }}
                    </div>
                </div>
                <div class="row p-2">
                    <label class="font-weight-bold">Status</label>
                    <div class="col-md-12 text-uppercase sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $callCenter->call_center_status == 1 ? 'PENDENTE' : 'RESOLVIDO' }}
                    </div>
                </div>

                <div class="row p-2">
                    <label class="font-weight-bold">Data</label>
                    <div class="col-md-12 text-uppercase sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $callCenter->created_at_formatted }}
                    </div>
                </div>
                @if ($callCenter->call_center_comments)
                    <div class="row p-2">
                        <label class="font-weight-bold">Observações</label>
                        <div class="col-md-12 text-uppercase sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                            {{ $callCenter->call_center_comments }}
                        </div>
                    </div>
                @endif

                @php
                    $previousCard = $previousCard->base_acesso_card_proxy ?? null;
                    // dd($previousCard);
                @endphp

                @if ($previousCard)
                    <div class="row p-2 mb-3">
                        <label class="font-weight-bold">
                            Proxy cancelado
                        </label>
                        <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                            {{ $previousCard }}
                        </div>
                    </div>
                @endif

                @php
                    $currencyCard = $currencyCard->base_acesso_card_proxy ?? null;
                @endphp

                @if ($currencyCard)
                    <div class="row p-2 mb-3">
                        <label class="font-weight-bold">
                            Proxy ativado
                        </label>
                        <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                            {{ $currencyCard }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('/js/clients/create-edit-client.js') }}"></script>
@endpush
