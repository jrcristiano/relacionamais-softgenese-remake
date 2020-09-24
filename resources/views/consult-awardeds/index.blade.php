@extends('layouts.admin')
@section('title', 'Consulta de premiados')
@section('content')
<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0"><i class="fas fa-bars"></i></button>
                <h2 class="sgi-content-title">Premiados</h2>
                @php
                    $pedidoId = $id ?? null;
                @endphp
                <a class="btn btn-primary sgi-btn-bold ml-auto mt-2" href="{{ route('admin.home') }}">
                    <i class="fas fa-undo"></i> Voltar
                </a>
            </header>

            @include('components.message')

            <div class="col-lg-12 mt-4 d-flex flex-nowrap mb-2">
                {!! $awardeds->appends(['pedido_id' => \Request::get('pedido_id')])->links() !!}
                <input id="filter_table" class="col-lg-3 ml-auto form-control mr-sm-2" type="text" placeholder="Nome, documento e etc." />
            </div>

            <table id="client_table" class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Número do cartão</th>
                        <th scope="col">Proxy</th>
                        <th scope="col">Status</th>
                        <th scope="col">Criado em</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($awardeds as $awarded)
                        <tr>
                            <td class="text-uppercase">{{ $awarded->acesso_card_name }}</td>
                            <td class="text-uppercase">{{ $awarded->acesso_card_document }}</td>
                            <td>R$ {{ $awarded->acesso_card_value_formatted }}</td>
                            <td>{{ $awarded->acesso_card_number }}</td>
                            <td>{{ $awarded->base_acesso_card_proxy }}</td>
                            @php
                                $status = $awarded->awarded_status == 1 ? 'Aberto' : ($statusAtribute == 2 ? 'Recebido' : 'Cancelado');
                            @endphp
                            <td class="text-uppercase">{{ $status }}</td>
                            <td>{{ $awarded->created_at_formatted }}</td>
                        </tr>
                    @empty
                        <td colspan="10" class="text-center"><i class="fas fa-frown"></i> Nenhuma premiação ainda registrada...</td>
                    @endforelse
                </tbody>
            </table>

            @if ($awardeds->count() >= 200)
                <div class="col-lg-4 d-flex justify-content-between p-3" style="margin: 0 auto; border-top: 2px solid #eee;">
                    {!! $awardeds->appends(['pedido_id' => \Request::get('pedido_id')])->links() !!}
                    <button id="sgi_btn_up" class="btn btn-lg btn-primary mr-3 mb-2"><i class="fas fa-arrow-up"></i></button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
