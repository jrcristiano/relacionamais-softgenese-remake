@extends('layouts.admin')
@section('title', 'Remessas')
@section('content')

@php
    //dd($awards)
@endphp

<div class="container-fluid">
    <div class="row shadow bg-white rounded">

        @include('components.leftbar')

        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            @include('components.header_content', [
                'title' => 'Remessas',
                'buttonTitle' => 'Voltar a home',
                'route' => 'admin.home',
                'icon' => 'fas fa-home'
            ])

            @if (\Request::get('tipo_premiacao') == 1)
                @if ($alert && $alert->acesso_card_generated == 1)
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                        <strong>Atenção! </strong> Há cartões não vinculados junto à processadora. Um arquivo de vinculação será criado.

                        <a id="generate-vincs" data-id="{{ $alert->acesso_card_award_id }}" href="{{ asset("/storage/shipments/{$alert->awaiting_payment_all_file}") }}" download="{{ $alert->awaiting_payment_all_file }}" class="alert-link">CLIQUE AQUI PARA GERAR O ARQUIVO DE VINCULAÇÃO</a>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            @endif

            @include('components.message')

            <div class="col-lg-12 mt-3 d-flex flex-nowrap mb-2 sgi-sub-content-header">

                <div class="w-75">
                    <form class="d-flex w-50" action="" method="get">
                        <select name="tipo_premiacao" class="form-control">
                            <option value="">SELECIONAR TIPO DE PREMIAÇÃO</option>
                            <option {{ \Request::get('tipo_premiacao') == 1 ? 'selected' : '' }} value="1">REMESSA ACESSOCARD COMPLETO</option>
                            <option {{ \Request::get('tipo_premiacao') == 2 ? 'selected' : '' }} value="2">REMESSA DEP. EM CONTA ITAÚ</option>
                        </select>
                        <button id="btn-date" type="submit" class="btn btn-primary mr-2 ml-2">
                            <i aria-hidden="true" class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                {!! $awards->links() !!}
                <input id="filter_table" class="col-lg-3 ml-auto form-control mr-sm-2" type="text" placeholder="Valor, tipo de premiação e etc." />
            </div>

            <table id="client_table" class="table table-sm table-striped table-hover mt-3">
                <thead>
                    <tr>
                        <th scope="col">ID pedido</th>
                        <th scope="col">Nota fiscal</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Tipo de premiação</th>
                        <th scope="col">Status</th>
                        <th scope="col">Data de emissão</th>
                        @if(\Request::get('tipo_premiacao'))
                            <th scope="col">Adic. a remessa</th>
                        @endif
                        <th scope="col">Ações</th>
                    </tr>
                    </thead>
                        <tbody>
                            @forelse ($awards as $award)
                                @php
                                    if ($award->awarded_type == 1 || $award->awarded_type == 2) {
                                        $status = $award->awarded_status == 1 ? 'EM REMESSA' : ($award->awarded_status == 2 ? 'AGUARDANDO PAGAMENTO' : ($award->awarded_status == 3 || $award->awarded_status == null ? 'PENDENTE' : ($award->awarded_status == 4 ? 'Cancelado' : '')));
                                    }

                                    if ($award->awarded_type == 3) {
                                        $status = $award->awarded_status == 1 || $award->awarded_status == null ? 'PAGO' : ($award->awarded_status == 3 ? 'PENDENTE' : ($award->awarded_status == 4 ? 'CANCELADO' : ''));
                                    }

                                    if ($award->shipment_generated) {
                                        $status = 'REMESSA GERADA';
                                    }

                                    if ($award->awarded_shipment_cancelled) {
                                        $status = 'REMESSA CANCELADA';
                                    }
                                @endphp
                                <tr>
                                    <td class="text-uppercase">PEDIDO {{ $award->awarded_demand_id }} | PREMIAÇÃO {{ $award->id }}</td>
                                    <td>{{ $award->note_fiscal ?? '' }}</td>
                                    <td>R$ {{ number_format($award->awarded_value, 2, ',', '.')  }}</td>
                                    <td class="text-uppercase">{{ $award->awarded_type == 1 ? 'CARTÃO ACESSO' : 'DEPÓSITO EM CONTA' }}</td>
                                    <td class="text-uppercase">{{ $status ?? '' }}</td>
                                    <td>{{ $award->created_at_formatted }}</td>
                                    @if(\Request::get('tipo_premiacao'))
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                @if ($award->shipment_generated)
                                                    <i class="fas fa-check text-success"></i>
                                                @else
                                                    @if (!$award->awarded_shipment_cancelled && $award->awarded_type == 1)
                                                        <input data-award="{{ $award->awarded_type }}" data-id="{{ $award->id }}" type="checkbox" class="custom-control-input check-id{{ $award->id }}" id="customCheck{{ $award->id }}">
                                                        <label class="custom-control-label" for="customCheck{{ $award->id }}"></label>
                                                    @elseif($award->awarded_shipment_cancelled)
                                                        <i aria-hidden="true" class="fas fa-close text-danger"></i>
                                                    @endif

                                                    @if (!$award->awarded_shipment_cancelled && $award->awarded_type == 2)
                                                        <input data-award="{{ $award->awarded_type }}" data-id="{{ $award->id }}" type="checkbox" class="custom-control-input check-id{{ $award->id }}" id="customCheck{{ $award->id }}">
                                                        <label class="custom-control-label" for="customCheck{{ $award->id }}"></label>
                                                    @elseif($award->awarded_shipment_cancelled)
                                                        <i aria-hidden="true" class="fas fa-close text-danger"></i>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                    <td>
                                        @if ($award->awarded_type == 1)
                                            <a data-toggle="tooltip" data-placement="top" title="Visualizar" class="btn btn-sm btn-primary" href="{{ route('admin.register.acesso-cards.show', ['id' => $award->id, 'pedido_id' => $award->awarded_demand_id]) }}">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        @endif
                                        @if ($award->awarded_type == 2)
                                            <a data-toggle="tooltip" data-placement="top" title="Visualizar" class="btn btn-sm btn-primary" href="{{ route('admin.register.awardeds.show', ['id' => $award->id, 'pedido_id' => $award->awarded_demand_id]) }}">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        @endif

                                        @if (!$award->awarded_shipment_cancelled)
                                            <form class="d-inline" action="{{ route('admin.api.shipment-api.update', ['id' => $award->id]) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="awarded_shipment_cancelled" value="1" />
                                                <button data-toggle="tooltip" data-placement="top" title="Cancelar remessa" class="btn btn-sm btn-danger sgi-cancel">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @else
                                            <div class="btn btn-sm btn-danger disabled">
                                                <i class="fas fa-ban"></i>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <td class="text-center" colspan="9">
                                    <i class="fas fa-frown"></i> Nenhuma remessa ainda registrada...
                                </td>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="container-fluid position-fixed bg-none py-3 d-none footer-bar" style="bottom: 0;">
                        <div class="row d-flex justify-content-around">
                            <div class="col-md-6">
                                <h5 class="sgi-content-title">
                                    <span class="font-weight-bold"></span>
                                </h5>
                            </div>
                            <div class="col-md-3 mt-2 mb-3">
                                <button class="btn btn-danger btn-lg button-send">
                                    <i class="fas fa-fire"></i> Gerar remessa
                                </button>
                                <a class="btn-lg btn-primary d-none btn-download">
                                    <i class="fas fa-download"></i> Baixar remessa
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($awards->count() >= 100)
                        <div class="col-lg-4 d-flex justify-content-between p-3" style="margin: 0 auto; border-top: 2px solid #eee;">
                            {!! $awards->links() !!}
                            <button id="sgi_btn_up" class="btn btn-lg btn-primary mr-3 mb-2"><i class="fas fa-arrow-up"></i></button>
                        </div>
                    @endif
        </div>
    </div>
</div>
@endsection

@if(\Request::get('tipo_premiacao') == 1)
    @push('scripts')
        <script src="{{ asset('/js/shipments/acesso-card.js') }}"></script>
    @endpush
@endif

@if(\Request::get('tipo_premiacao') == 2)
    @push('scripts')
        <script src="{{ asset('/js/shipments/deposit-account.js') }}"></script>
    @endpush
@endif
