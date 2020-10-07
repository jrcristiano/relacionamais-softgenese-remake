@extends('layouts.admin')
@section('title', 'Consulta de premiados')
@section('content')
@php
    // dd($awardeds)
@endphp

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0"><i class="fas fa-bars"></i></button>
                <h2 class="sgi-content-title">Consulta de premiados</h2>
                @php
                    $pedidoId = $id ?? null;
                @endphp
                <a class="btn btn-primary sgi-btn-bold ml-auto mt-2" href="{{ route('admin.home') }}">
                    <i class="fas fa-undo"></i> Voltar
                </a>
            </header>

            @include('components.message')

            <div class="col-lg-12 mt-4 d-flex flex-nowrap mb-2">
                <input id="filter_table" type="text" placeholder="Nome, documento e etc." class="col-lg-3 ml-auto form-control mr-sm-2">
            </div>

            <table id="client_table" class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Número do cartão</th>
                        <th scope="col">Proxy</th>
                        <th scope="col">Status do cartão</th>
                        <th scope="col">Status</th>
                        <th scope="col">Data</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($awardeds as $awarded)
                        <tr>
                            <td class="text-uppercase">{{ $awarded->acesso_card_name }}</td>
                            <td class="text-uppercase">{{ $awarded->acesso_card_document }}</td>
                            <td>R$ {{ $awarded->acesso_card_value_formatted }}</td>
                            <td>{{ $awarded->base_acesso_card_number }}</td>
                            <td>{{ $awarded->base_acesso_card_proxy }}</td>
                            @php
                                $status = $awarded->awarded_status == 3 ? 'Pendente' : ($awarded->awarded_status == 2 ? 'Aguardando pagamento' : 'Em remessa');

                                if ($awarded->acesso_card_generated) {
                                    $status = 'Remessa gerada';
                                }

                                if ($awarded->acesso_card_chargeback) {
                                    $status = 'Remessa cancelada';
                                }

                            @endphp
                            <td class="text-uppercase">
                                {{ $awarded->base_acesso_card_status == 1 ? 'Ativo' : ($awarded->base_acesso_card_status == 2 ? 'Cancelado' : '') }}
                            </td>
                            <td class="text-uppercase">{{ $status }}</td>
                            <td>{{ $awarded->created_at_formatted }}</td>
                        </tr>
                    @empty
                        <td colspan="10" class="text-center">
                            <i class="fas fa-frown"></i> Nenhuma premiação ainda registrada...
                        </td>
                    @endforelse
                </tbody>
            </table>

            @if ($awardeds->count() >= 500)
                <div class="col-lg-4 d-flex justify-content-between p-3" style="margin: 0 auto; border-top: 2px solid #eee;">
                    {!! $awardeds->appends(['pedido_id' => \Request::get('pedido_id')])->links() !!}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
