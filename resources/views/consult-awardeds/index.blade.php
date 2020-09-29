@extends('layouts.admin')
@section('title', 'Consulta de premiados')
@section('content')

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
                <form class="d-flex w-100" action="" method="get">
                    <select name="nome" class="form-control text-uppercase">
                        <option value="">FILTRAR POR NOME</option>
                        @foreach ($filters as $filter)
                            <option {{ \Request::get('nome') == $filter->base_acesso_card_name ? 'selected' : '' }} value="{{ $filter->base_acesso_card_name }}">
                                {{ $filter->base_acesso_card_name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="cpf" class="form-control ml-2">
                        <option value="">FILTRAR POR CPF</option>
                        @foreach ($filters as $filter)
                            <option {{ \Request::get('cpf') == $filter->base_acesso_card_cpf ? 'selected' : '' }} value="{{ $filter->base_acesso_card_cpf }}">{{ $filter->base_acesso_card_cpf }}</option>
                        @endforeach
                    </select>
                    <select name="proxy" class="form-control ml-2">
                        <option value="">FILTRAR POR PROXY</option>
                        @foreach ($filters as $filter)
                            <option {{ \Request::get('proxy') == $filter->base_acesso_card_proxy ? 'selected' : '' }} value="{{ $filter->base_acesso_card_proxy }}">
                                {{ $filter->base_acesso_card_proxy }}
                            </option>
                        @endforeach
                    </select>
                    <button id="btn-date" type="submit" class="btn btn-primary mr-2 ml-2">
                        <i aria-hidden="true" class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <table id="client_table" class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Número do cartão</th>
                        <th scope="col">Status do cartão</th>
                        <th scope="col">Proxy</th>
                        <th scope="col">Status</th>
                        <th scope="col">Criado em</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($awardeds as $awarded)
                        <tr>
                            <td class="text-uppercase">{{ $awarded->base_acesso_card_name }}</td>
                            <td class="text-uppercase">{{ $awarded->base_acesso_card_cpf }}</td>
                            <td>R$ {{ $awarded->acesso_card_value_formatted }}</td>
                            <td>{{ $awarded->base_acesso_card_number }}</td>
                            <td class="text-uppercase">
                                {{ $awarded->base_acesso_card_status == 1 ? 'Ativo' : ($awarded->base_acesso_card_status == 2 ? 'Cancelado' : 'Reservado') }}
                            </td>
                            <td>{{ $awarded->base_acesso_card_proxy }}</td>
                            @php
                                $status = $awarded->awarded_status == 3 ? 'Pendente' : ($awarded->awarded_status == 2 ? 'Aguardando pagamento' : 'Em remessa');
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
