@extends('layouts.admin')
@section('title', 'Premiações')
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="sgi-content-title">Premiações</h2>
                @php
                    $pedidoId = $id ?? null;
                @endphp
                <a class="btn btn-primary sgi-btn-bold ml-auto mt-2" href="{{ route('admin.home') }}">
                    <i class="fas fa-undo"></i> Voltar
                </a>
            </header>

            @include('components.message')

            <div class="col-lg-12 mt-4 d-flex flex-nowrap mb-2">

            </div>

            <table id="client_table" class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Empresa</th>
                        <th scope="col">Número do cartão</th>
                        <th scope="col">Proxy do cartão</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Status da premiação</th>
                        <th scope="col">Data</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($acessoCards as $acessoCard)
                        <tr>
                            <td class="text-uppercase">{{ $acessoCard->demand_client_name }}</td>
                            <td class="text-uppercase">{{ $acessoCard->acesso_card_number }}</td>
                            <td>{{ $acessoCard->acesso_card_proxy }}</td>
                            <td>{{ $acessoCard->acesso_card_value_formatted }}</td>
                            <td class="text-uppercase">
                               {{ $acessoCard->awarded_status === 3 ? 'PENDENTE' : ($acessoCard->awarded_status === 2 ? 'AGUARDANDO PAGAMENTO' : ($acessoCard->awarded_status === 1 && $acessoCard->shipment_generated === null ? 'ENVIADO PARA REMESSA' : ($acessoCard->awarded_status === 1 && $acessoCard->shipment_generated === 1 ? 'REMESSA GERADA' : ''))) }}
                            </td>
                            <td>{{ $acessoCard->created_at_formatted }}</td>
                        </tr>
                    @empty
                        <td colspan="10" class="text-center">
                            <i class="fas fa-frown"></i> Nenhuma premiação ainda registrada...
                        </td>
                    @endforelse
                </tbody>
            </table>

            @if ($acessoCards->count() >= 500)
                <div class="col-lg-4 d-flex justify-content-between p-3" style="margin: 0 auto; border-top: 2px solid #eee;">
                    {!! $acessoCards->appends(['pedido_id' => \Request::get('pedido_id')])->links() !!}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection