@extends('layouts.admin')
@section('title', 'Consulta de acesso cards')
@section('content')
@php
    // dd($awardeds)
@endphp

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="sgi-content-title">Cartões AcessoCard</h2>
                @php
                    $pedidoId = $id ?? null;
                @endphp
                <a class="btn btn-primary sgi-btn-bold ml-auto mt-2" href="{{ route('admin.home') }}">
                    <i class="fas fa-undo"></i> Voltar
                </a>
            </header>

            @include('components.message')

            <div class="col-lg-12 mt-4 d-flex flex-nowrap mb-2">
                <form class="col-md-12 d-flex p-0" action="" method="get">
                    <input class="form-control w-25" value="{{ old('search', \Request::get('search')) }}" type="text" name="search" id="search" placeholder="Nome, CPF, número do cartão e proxy..." />

                    <button id="btn-date" class="btn btn-primary mr-2 ml-2" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <table id="client_table" class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Número do cartão</th>
                        <th scope="col">Proxy</th>
                        <th scope="col">Status do cartão</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($awardeds as $awarded)
                        <tr>
                            <td class="text-uppercase">{{ $awarded->acesso_card_name }}</td>
                            <td class="text-uppercase">{{ $awarded->acesso_card_document }}</td>
                            <td>{{ $awarded->base_acesso_card_number }}</td>
                            <td>{{ $awarded->base_acesso_card_proxy }}</td>
                            <td class="text-uppercase">
                                {{ $awarded->base_acesso_card_status == 1 ? 'Ativo' : ($awarded->base_acesso_card_status == 2 ? 'Cancelado' : '') }}
                            </td>
                            <td>
                                <a data-toggle="tooltip" data-placement="top" title="Visualizar" class="btn btn-sm btn-primary" href="{{ route('admin.operational.consult-acesso-cards.show', ['proxy' => $awarded->base_acesso_card_proxy]) }}">
                                    <i class="far fa-eye"></i>
                                </a>
                            </td>
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
