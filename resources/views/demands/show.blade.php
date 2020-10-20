@extends('layouts.admin')
@section('title', "PEDIDO {$demand->demand_client_name}")
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            <ul class="nav nav-tabs mt-2 px-1" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ \Request::get('premiacao') ? '' : 'active' }}" id="demand-tab" data-toggle="tab" href="#demand" role="tab" aria-controls="demand" aria-selected="true">
                        <i class="fas fa-home"></i> Pedido
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ \Request::get('premiacao') ? 'active' : '' }}" id="shipments-tab" data-toggle="tab" href="#shipments" role="tab" aria-controls="shipments" aria-selected="{{ \Request::get('premiacao') ? 'true' : 'false' }}">
                        <i class="fas fa-rocket"></i> Premiações
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="nfe-tab" data-toggle="tab" href="#nfe" role="tab" aria-controls="nfe" aria-selected="false">
                        <i class="far fa-sticky-note"></i> Nota Fiscal
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane {{ \Request::get('premiacao') ? '' : 'fade show active'  }}" id="demand" role="tabpanel" aria-labelledby="demand-tab">

                    <header class="sgi-content-header d-flex align-items-center">
                        <button id="sgi-mobile-menu" class="btn btn btn-primary mr-3 rounded-0 "><i class="fas fa-bars"></i></button>
                        <h1 class="font-weight-bold sgi-content-title">ID {{ $demand->id }} | {{ $demand->demand_client_name }}</h1>
                        <a class="btn btn-primary ml-auto mt-2 mr-1" href="{{ route('admin.home') }}">
                            <i class="fas fa-undo"></i> Voltar
                        </a>
                        <a href="{{ route('admin.edit', [ 'id' => $demand->id ]) }}" class="btn btn-primary mt-2 mr-1">
                            <i aria-hidden="true" class="fas fa-edit"></i> Editar
                        </a>
                    </header>

                    <div class="container-fluid mt-2">
                        <div class="row p-3">
                            <label class="font-weight-bold">Valor de premiação</label>
                            <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                                R$ {{ $demand->demand_prize_amount_formatted }}
                            </div>
                        </div>
                        <div class="row p-3">
                            <label class="font-weight-bold">Valor Tributável</label>
                            <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                            R$ {{ $demand->demand_taxable_amount_formatted }}
                            </div>
                        </div>
                        <div class="row p-3">
                            <label class="font-weight-bold">Outros valores</label>
                            <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                                R$ {{ $demand->demand_other_value_formatted }}
                            </div>
                        </div>
                        <div class="row p-3">
                            <label class="font-weight-bold">Saldo</label>
                            <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                                R$ {{ number_format($demand->sale_formatted, 2, ',', '.') }}
                            </div>
                        </div>
                        <div class="row p-3">
                            <label class="font-weight-bold">Criado em</label>
                            <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                                {{ $demand->created_at_formatted }}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-pane {{ \Request::get('premiacao') ? 'fade show active' : ''  }}" id="shipments" role="tabpanel" aria-labelledby="shipments-tab">
                    <header class="sgi-content-header d-flex align-items-center">
                        <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0"><i class="fas fa-bars"></i></button>
                            <h3 class="sgi-content-title">Premiações</h3>

                            <div class="dropdown ml-auto">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-plus"></i> Nova premiação
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ route('admin.register.awardeds.create', ['pedido_id' => $id]) }}">
                                        Depósito em conta
                                    </a>
                                  <a class="dropdown-item" href="{{ route('admin.register.payment-manuals.create', ['pedido_id' => $id]) }}">
                                      Pagamento manual
                                  </a>
                                  <a class="dropdown-item" href="{{ route('admin.register.acesso-cards.create', ['pedido_id' => $id]) }}">
                                      Acesso card completo
                                  </a>
                                </div>
                              </div>
                    </header>

                    <div class="col-lg-12">
                        {!! $awards->links() !!}
                        <input id="filter_table" class="col-lg-3 ml-auto form-control mr-sm-1 mt-3" type="text" placeholder="Pesquisar premiações" />
                    </div>

                    <table id="client_table" class="table table-sm table-striped table-hover mt-3">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Tipo de premiação</th>
                            <th scope="col">Status</th>
                            <th scope="col">Criado em</th>
                            <th scope="col">Ações</th>
                        </tr>
                        </thead>
                            <tbody>
                                @forelse ($awards as $award)
                                    <tr>
                                        <th class="text-uppercase">{{ $award->id }}</th>
                                        <td>R$ {{ $award->awarded_value_formatted }}</td>
                                        <td class="text-uppercase">{{ $award->awarded_type == 3 ? 'PAGAMENTO MANUAL' : ($award->awarded_type == 2 ? 'DEPÓSITO EM CONTA' : ($award->awarded_type == 1 ? 'CARTÃO ACESSO' : '')) }}</td>
                                        @php
                                            if ($award->awarded_type == 1) {
                                                $status = $award->awarded_status == 1 ? 'ENVIADO PARA REMESSA' : ($award->awarded_status == 2 ? 'AGUARDANDO PAGAMENTO' : ($award->awarded_status == 3 || $award->awarded_status == null ? 'PENDENTE' : ($award->awarded_status == 4 ? 'CANCELADO' : '')));
                                            }

                                            if ($award->awarded_type == 2) {
                                                $status = $award->awarded_status == 1 ? 'ENVIADO PARA REMESSA' : ($award->awarded_status == 2 ? 'AGUARDANDO PAGAMENTO' : ($award->awarded_status == 3 || $award->awarded_status == null ? 'PENDENTE' : ($award->awarded_status == 4 ? 'CANCELADO' : '')));
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
                                        <td class="text-uppercase">{{ $status }}</td>
                                        <td>{{ $award->created_at_formatted }}</td>

                                        <td>

                                            @if ($award->awarded_type == 1 && $award->awarded_status == 4)
                                                <a href="{{ route('admin.register.acesso-cards.show', [ 'id' => $award->id, 'pedido_id' => $demand->id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            <!-- Cartão acesso card (pendente) -->
                                            @if ($award->awarded_type == 1 && $award->awarded_status == 3)
                                                <a href="{{ route('admin.register.acesso-cards.show', [ 'id' => $award->id, 'pedido_id' => $demand->id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.register.awardeds.edit', [ 'id' => $award->id, 'pedido_id' => $id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-edit"></i>
                                                </a>
                                                <form id="form_delete" class="d-inline" action="{{ route('admin.register.awardeds.destroy', [ 'id' => $award->id, 'pedido_id' => $id ]) }}" method="post">
                                                    @csrf
                                                    <button id="btn_delete" data-placement="top" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Acesso card (enviado para remessa) -->
                                            @if ($award->awarded_type == 1 && $award->awarded_status == 1)
                                                <a href="{{ route('admin.register.acesso-cards.show', [ 'id' => $award->id, 'pedido_id' => $demand->id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            <!-- Dep. em conta (enviado para remessa) -->
                                            @if ($award->awarded_type == 2 && $award->awarded_status == 1)
                                                <a href="{{ route('admin.register.awardeds.show', [ 'id' => $award->id, 'pedido_id' => $demand->id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.register.awardeds.edit', [ 'id' => $award->id, 'pedido_id' => $id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            <!-- Pagamento manual (cancelado) -->
                                            @if ($award->awarded_type == 3 && $award->awarded_status == 4 || $award->awarded_type == 3 && $award->awarded_status == 1)
                                                <a href="{{ route('admin.register.awardeds.show', [ 'id' => $award->id, 'pedido_id' => $demand->id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.register.awardeds.edit', [ 'id' => $award->id, 'pedido_id' => $id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            @if ($award->awarded_type == 2 && $award->awarded_status == 2)
                                                <a href="{{ route('admin.register.awardeds.show', [ 'id' => $award->id, 'pedido_id' => $demand->id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('admin.register.awardeds.edit', [ 'id' => $award->id, 'pedido_id' => $id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            <!-- Dep. em conta (pendente) -->
                                            @if ($award->awarded_type == 2 && $award->awarded_status == 3)
                                                <a href="{{ route('admin.register.awardeds.show', [ 'id' => $award->id, 'pedido_id' => $demand->id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.register.awardeds.edit', [ 'id' => $award->id, 'pedido_id' => $id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-edit"></i>
                                                </a>
                                                <form id="form_delete" class="d-inline" action="{{ route('admin.register.awardeds.destroy', [ 'id' => $award->id, 'pedido_id' => $id ]) }}" method="post">
                                                    @csrf
                                                    <button id="btn_delete" data-placement="top" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($award->awarded_type == 1 && $award->awarded_status == 2)
                                                <a href="{{ route('admin.register.acesso-cards.show', [ 'id' => $award->id, 'pedido_id' => $demand->id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.register.awardeds.edit', [ 'id' => $award->id, 'pedido_id' => $id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                        </td>
                                    </tr>
                                @empty
                                    <td class="text-center" colspan="6"><i class="fas fa-frown"></i> Nenhuma premiação ainda registrada...</td>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="nfe" role="tabpanel" aria-labelledby="nfe-tab">
                        <header class="sgi-content-header d-flex align-items-center">
                            <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0"><i class="fas fa-bars"></i></button>
                                <h3 class="sgi-content-title">Notas fiscais</h3>
                            @php
                                $noteExists = $notes->count() >= 1;
                            @endphp
                            @if (!$noteExists)
                                <a class="btn btn-primary sgi-btn-bold ml-auto" href="{{ route('admin.financial.notes.create', ['pedido_id' => $id]) }}">
                                    <i class="fas fa-plus"></i> Nova nota fiscal
                                </a>
                            @else
                                <a class="btn btn-primary sgi-btn-bold ml-auto" href="{{ route('admin.financial.notes.create', ['pedido_id' => $id]) }}">
                                    <i class="fas fa-home"></i> Voltar a home
                                </a>
                            @endif
                        </header>

                        <div class="col-lg-12">
                            {!! $awards->links() !!}
                            <input id="filter_table_nfe" class="col-lg-3 ml-auto form-control mr-sm-1 mt-3" type="text" placeholder="Pesquisar notas fiscais" />
                        </div>

                        <table id="client_table" class="table table-sm table-striped table-hover mt-3">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Número de NF</th>
                                <th scope="col">Status</th>
                                <th scope="col">Data de venc.</th>
                                <th scope="col">Data de emissão</th>
                                <th scope="col">Valor de prem.</th>
                                <th scope="col">Valor tribut.</th>
                                <th scope="col">Data de recebim.</th>
                                <th scope="col">Ações</th>
                            </tr>
                            </thead>
                                <tbody>
                                    @forelse ($notes as $note)
                                        <tr>
                                            <th scope="row">{{ $note->id }}</th>
                                            <th scope="row">{{ $note->note_number }}</th>
                                            <td class="text-uppercase" scope="row">{{ $note->note_status_formatted }}</td>
                                            <td scope="row">{{ $note->note_due_date_formatted }}</td>
                                            <td scope="row">{{ $note->created_at_formatted }}</td>
                                            <td scope="row">R$ {{ $note->demand_prize_amount_formatted }}</td>
                                            <td scope="row">R$ {{ $note->demand_taxable_amount_formatted }}</td>
                                            <td scope="row">{{ $note->note_receipt_date_formatted }}</td>

                                            <td >
                                                <a href="{{ route('admin.financial.notes.edit', ['id' => $note->id, 'pedido_id' => $id ]) }}" class="btn btn-sm btn-primary">
                                                    <i aria-hidden="true" class="fas fa-edit"></i>
                                                </a>
                                                @if ($note->receipts == 0 || $note->note_status != 2)
                                                    <form id="form_delete" class="d-inline" action="{{ route('admin.financial.notes.destroy', ['id' => $note->id ]) }}" method="post">
                                                        @csrf
                                                        <button id="btn_delete" data-placement="top" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <td class="text-center" colspan="10"><i class="fas fa-frown"></i> Nenhuma nota fiscal ainda registrada...</td>
                                    @endforelse
                                </tbody>
                            </table>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection


