@extends('layouts.admin')
@section('title', 'Editar nota fiscal')
@section('content')
@php
    // dd($note)
@endphp
<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">

        <header class="sgi-content-header d-flex align-items-center">
            <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0"><i class="fas fa-bars"></i></button>
            <h2 class="sgi-content-title">Editar nota fiscal</h2>
            @php
                $pedidoId = $id ?? null;
            @endphp

            <a href="{{ route('admin.financial.note-receipts.create', [ 'nota_id' => $id, 'recebimento_id' => \Request::get('recebimento_id'), 'pedido_id' => \Request::get('pedido_id') ]) }}" type="button" class="btn btn-primary sgi-btn-bold mt-2 ml-auto mr-2">
                <i class="fas fa-hand-holding-usd"></i> Novo recebimento
            </button>

            <a class="btn btn-primary sgi-btn-bold mt-2" href="{{ route('admin.home') }}">
                <i class="fas fa-home"></i> Voltar a home
            </a>

        </header>
            <div class="col-md-12 pl-0 pr-0 d-flex justify-content-between" style="margin-bottom:10%;">
                <form class="mt-4 px-1 col-md-3 py-3" action="{{ route('admin.financial.notes.update', ['id' => $id, 'pedido_id' => \Request::get('pedido_id')]) }}" method="post">
                    @method('PUT')
                    @include('components.forms.form_note')
                    <div class="form-group">
                        <button class="btn btn-success font-weight-bold mt-1" type="submit">
                            <i class="fas fa-arrow-right"></i> Salvar
                        </button>
                    </div>
                </form>

                <div class="col-md-9 mt-1 pr-0">
                    <div class="col-md-12 mt-1 d-flex flex-nowrap mb-2 pl-0">
                        <header class="sgi-content-header d-flex align-items-center pl-0">
                            <h2 class="sgi-content-title">Recebimentos</h2>
                        </header>

                        <input id="filter_table" class="col-lg-4 ml-auto form-control mr-sm-2 mt-2" type="text" placeholder="Pesquisar recebimentos.." />
                    </div>

                    <table id="client_table" class="table table-sm table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Patrimônio</th>
                                <th scope="col">Outros val.</th>
                                <th scope="col">Premiação</th>
                                <th scope="col">Data de recebimento</th>
                                <th scope="col">Conta de recebimento</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($noteReceipts as $noteReceipt)
                            <tr>
                                <td>{{ $noteReceipt->note_receipt_taxable_real_value_formatted }}</td>
                                <td>{{ $noteReceipt->note_receipt_other_value_formatted }}</td>
                                <td>{{ $noteReceipt->note_receipt_award_real_value_formatted }}</td>
                                <td>{{ $noteReceipt->note_receipt_date_formatted }}</td>
                                <td class="text-uppercase">{{ $noteReceipt->bank_name }} | AG {{ $noteReceipt->bank_agency }} | Conta {{ $noteReceipt->bank_account }}</td>
                                <td>
                                    <a data-toggle="tooltip" data-placement="top" title="Editar" data-toggle="modal-update" data-target="#exampleModaUpdate" class="btn btn-sm btn-primary" href="{{ route('admin.financial.note-receipts.edit', [ 'id' => $noteReceipt->id, 'nota_id' => $id, 'recebimento_id' => \Request::get('recebimento_id'), 'pedido_id' => \Request::get('pedido_id') ]) }}">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <form class="d-inline" action="{{ route('admin.financial.note-receipts.delete', ['id' => $noteReceipt->id, 'pedido_id' => \Request::get('pedido_id'), 'nota_id' => \Request::get('nota_id')]) }}" method="post">
                                        @csrf
                                        <button data-toggle="tooltip" data-placement="top" title="Remover" class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <td colspan="6" class="text-center"><i class="fas fa-frown"></i> Nenhum recebimento ainda registrado...</td>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="container-fluid position-fixed py-4 bg-white" style="bottom: 0; border-top: 1px solid #DDD;">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-3 mt-3">
                            <h5 class="sgi-content-title">
                            <span class="font-weight-bold">Patrimônio R$ {{ number_format($patrimony, 2, ',', '.') }}</span>
                            </h5>
                        </div>
                        <div class="col-md-3 mt-3">
                            <h5 class="sgi-content-title">
                                <span class="font-weight-bold">Premiação R$ {{ number_format($award, 2, ',', '.') }}</span>
                            </h5>
                        </div>
                        <div class="col-md-3 mt-3">
                            <h5 class="sgi-content-title">
                                <span class="font-weight-bold">Saldo R$ {{ number_format($sale, 2, ',', '.') }}</span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('/js/notes/create-edit-notes.js') }}"></script>
@endpush
