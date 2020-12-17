@extends('layouts.admin')
@section('title', 'Premiações')
@section('content')

@php
    // dd($acessoCards)
@endphp

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="sgi-content-title">{{ $acessoCardShoppings[0]->acesso_card_shopping_name }} | {{ $acessoCardShoppings[0]->acesso_card_shopping_document }}</h2>
                @php
                    $pedidoId = $id ?? null;
                @endphp

                <a class="btn btn-primary ml-auto mt-2 font-weight-bold mr-1"
                data-toggle="tooltip"
                data-placement="top"
                title="Novo chamado"
                href="{{ route('admin.operational.call-center.create', [
                    'tipo_cartao' => 'compras',
                    'premiado' => $acessoCardShoppings[0]->acesso_card_shopping_name,
                    'document' => $acessoCardShoppings[0]->acesso_card_shopping_document,
                    'acesso_card_id' => \Request::get('acesso_card_id')
                ]) }}">
                    <i class="fas fa-headset"></i> Novo chamado
                </a>
                <a class="btn btn-primary sgi-btn-bold mt-2" href="{{ url()->previous() }}">
                    <i class="fas fa-undo"></i> Voltar
                </a>
            </header>

            @include('components.message')

            <div class="col-lg-12 mt-4 d-flex flex-nowrap mb-2">

            </div>

            <table id="client_table" class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope>ID pedido</th>
                        <th scope>ID premiação</th>
                        <th scope="col">Empresa</th>
                        <th scope="col">Número do cartão</th>
                        <th scope="col">Proxy do cartão</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Status da premiação</th>
                        <th scope="col">Data</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($acessoCardShoppings as $acessoCard)
                        <tr>
                            <th class="text-uppercase">{{ $acessoCard->demand_id }}</td>
                            <th class="text-uppercase">{{ $acessoCard->award_id }}</td>
                            <td class="text-uppercase">
                                {{ $acessoCard->demand_client_name }}
                            </td>
                            <td class="text-uppercase">{{ $acessoCard->base_acesso_card_number }}</td>
                            <td>{{ $acessoCard->base_acesso_card_proxy }}</td>
                            <td>{{ $acessoCard->acesso_card_shopping_value_formatted }}</td>
                            @php
                            // dd($acessoCard->awarded_status)
                        @endphp

                            @php
                                $status = '';

                                if ($acessoCard->awarded_status == 3) {
                                    $status = 'PENDENTE';
                                }

                                if ($acessoCard->awarded_status == 2) {
                                    $status = 'AGUARDANDO PAGAMENTO';
                                }

                                if ($acessoCard->awarded_status == 1 && $acessoCard->shipment_generated == null) {
                                    $status = 'ENVIADO PARA REMESSA';
                                }

                                if ($acessoCard->awarded_status == 1 && $acessoCard->shipment_generated == 1) {
                                    $status = 'REMESSA GERADA';
                                }

                                if (($acessoCard->awarded_status == 1 || $acessoCard->awarded_status == 4) && $acessoCard->acesso_card_chargeback == 1) {
                                    $status = 'CANCELADO';
                                }
                            @endphp
                            <td class="text-uppercase">{{ $status }}</td>
                            <td>{{ $acessoCard->created_at_formatted }}</td>
                        </tr>
                    @empty
                        <td colspan="10" class="text-center">
                            <i class="fas fa-frown"></i> Nenhuma premiação ainda registrada...
                        </td>
                    @endforelse
                </tbody>
            </table>

            @if ($acessoCardShoppings->count() >= 500)
                <div class="col-lg-4 d-flex justify-content-between p-3" style="margin: 0 auto; border-top: 2px solid #eee;">
                    {!! $acessoCardShoppings->appends(['pedido_id' => \Request::get('pedido_id')])->links() !!}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
