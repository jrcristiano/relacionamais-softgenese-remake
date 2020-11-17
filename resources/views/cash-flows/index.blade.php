@extends('layouts.admin')
@section('content')
@section('title', 'Fluxo de caixa')

@php
    // dd($cashFlows);
@endphp

<div class="container-fluid">
        <div class="row shadow bg-white rounded">
            @include('components.leftbar')
                <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
                    @include('components.header_content', [
                        'title' => 'Fluxo de caixa',
                        'buttonTitle' => 'Voltar',
                        'route' => url()->previous(),
                        'icon' => 'fas fa-undo'
                    ])

                    <div class="col-md-12 mt-2">
                        @if($cashFlows->count() >= 200)
                            {!!
                                $cashFlows->appends([
                                    'cash_flow_in' => \Request::get('cash_flow_in'),
                                    'cash_flow_until' => \Request::get('cash_flow_until'),
                                    'cash_flow_bank' => \Request::get('cash_flow_bank')
                                ])
                                ->links()
                            !!}
                        @endif
                    </div>

                @include('components.message')

                <div class="col-md-12 mt-4 d-flex flex-nowrap mb-2">

                    <form id="date" class="col-md-12 p-0 d-flex" action="" method="get">
                        <label class="font-weight-bold mt-2 mr-2" for="cash_flow_in">De </label>
                        <input class="form-control" type="date" value="{{ old('cash_flow_in', \Request::get('cash_flow_in')) }}" name="cash_flow_in" id="cash_flow_in" />

                        <label class="font-weight-bold mt-2 ml-3 mr-2" for="cash_flow_until">Até </label>
                        <input class="form-control" type="date" value="{{ old('cash_flow_in', \Request::get('cash_flow_until')) }}" name="cash_flow_until" id="cash_flow_until" />
                        &nbsp;
                        <select class="form-control ml-2 mr-2" name="cash_flow_bank" id="cash_flow_bank">
                            <option value="">SELECIONAR POR BANCO, AGÊNCIA E CONTA</option>
                            @foreach ($banks as $bank)
                                <option {{ \Request::get('cash_flow_bank') == $bank->id ? 'selected' : '' }} value="{{ $bank->id }}">BANCO {{ $bank->bank_name }} | AG {{ $bank->bank_agency }} | CONTA {{ $bank->bank_account }}</option>
                            @endforeach
                        </select>
                        &nbsp;
                        <button id="btn-date" class="btn btn-primary mr-2" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <table id="client_table" class="table table-striped table-hover"  style="margin-bottom:15%;">
                    <thead>
                        <tr>
                            <th scope="col">Sacado</th>
                            <th scope="col">Documento</th>
                            <th class="sgi-show-or-not" scope="col">Banco</th>
                            <th scope="col">Data de moviment.</th>
                            <th scope="col">Patrimônio</th>
                            <th scope="col">Premiação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cashFlows as $cashFlow)
                        <tr>
                            <td class="text-uppercase">{{ $cashFlow->transfer_id ? 'Transferência' : ($cashFlow->client_company_formatted ?? $cashFlow->provider_name_formatted) }}</td>
                            @php
                                if ($cashFlow->bill_id && $cashFlow->bill_value) {
                                    $document = "ID {$cashFlow->bill_id}";
                                    $url = route('admin.financial.bills.show', [ 'id' => $cashFlow->bill_id ]);
                                    $tooltipTitle = 'Conta a pagar';
                                }

                                if ($cashFlow->flow_award_id) {
                                    $document = "PEDIDO {$cashFlow->awarded_demand_id} | PREMIAÇÃO {$cashFlow->flow_award_id}";
                                    $url = route('admin.show', [ 'id' => $cashFlow->awarded_demand_id, 'pedido_id' => $cashFlow->flow_demand_id ]);
                                    $tooltipTitle = 'Premiação | Débito';
                                }

                                if ($cashFlow->flow_receive_id && $cashFlow->note_number) {
                                    $document = "NF {$cashFlow->note_number}";
                                    $url = route('admin.financial.notes.edit', [ 'id' => $cashFlow->note_id, 'pedido_id' => $cashFlow->flow_demand_id ]);
                                    $tooltipTitle = 'Premiação | Crédito';
                                }

                                if ($cashFlow->transfer_id) {
                                    $document = "ID {$cashFlow->transfer_id}";
                                    $url = route('admin.financial.transfers.edit', [ 'id' => $cashFlow->transfer_id ]);
                                    $tooltipTitle = 'Transferência';
                                }
                            @endphp
                            <td class="cash_flow_document">
                                <a class="text-dark" data-toggle="tooltip" data-placement="top" data-original-title="{{ $tooltipTitle ?? null }}" href="{{ $url ?? null }}">{{ $document ?? '' }}</a>
                            </td>
                            <td class="text-uppercase">{{ $cashFlow->bank_name }} | AG {{ $cashFlow->bank_agency }} | Conta {{ $cashFlow->bank_account }}</td>
                            <td class="">{{ $cashFlow->flow_movement_date_formatted }}</td>
                            @php
                                $formattedShipmentValue = $cashFlow->shipment_value_formatted;

                                if ($cashFlow->bill_value && $cashFlow->bill_id) {
                                    $billValue = "R$ -{$cashFlow->bill_value}";
                                }

                                if ($cashFlow->shipment_value) {
                                    $shipmentValue = $cashFlow->shipment_value;
                                }

                                $transferValue = $cashFlow->transfer_value_formatted;
                                $transferValueCredit = "R$ {$transferValue}";
                                $transferValueDebit = "R$ -{$transferValue}";

                                if ($cashFlow->flow_transfer_credit_or_debit == 1 && $cashFlow->transfer_type == 1) {
                                    $transferValue = $transferValueCredit;
                                }

                                if ($cashFlow->flow_transfer_credit_or_debit == 2 && $cashFlow->transfer_type == 1) {
                                    $transferValue = $transferValueDebit;
                                }

                                if ($cashFlow->transfer_type == 1) {
                                    $patrimonyRow = $transferValue;

                                } else if ($cashFlow->bill_value && $cashFlow->bill_id) {
                                    $patrimonyRow = 'R$ -' . number_format($cashFlow->bill_value, 2, ',', '.');

                                } else if ($cashFlow->patrimony) {
                                    $patrimonyRow = "R$ {$cashFlow->patrimony}";
                                }

                            @endphp

                            <td>{{ $patrimonyRow }}</td>
                            @php
                                $awardedValue = number_format($cashFlow->awarded_value, 2, ',', '.');
                                $awardedValue = "R$ -{$awardedValue}";

                                if ($cashFlow->flow_transfer_credit_or_debit == 1 && $cashFlow->transfer_type == 2) {
                                    $transferValue = $transferValueCredit;
                                }

                                if ($cashFlow->award_value) {
                                    $premiationRow = 'R$ ' . $cashFlow->award_value;
                                }

                                if ($cashFlow->awarded_value) {
                                    $premiationRow = $awardedValue;
                                }

                                if ($cashFlow->shipment_value) {
                                    $premiationRow = number_format($cashFlow->shipment_value, 2, ',', '.');
                                    $premiationRow = "R$ -{$premiationRow}";
                                }

                                if ($cashFlow->flow_transfer_credit_or_debit == 2 && $cashFlow->transfer_type == 2) {
                                    $transferValue = $transferValueDebit;
                                }

                                if ($cashFlow->transfer_type == 2) {
                                    $premiationRow = $transferValue;
                                }
                            @endphp

                            <td>{{ $premiationRow }}</td>
                        </tr>
                        @empty

                        @endforelse
                    </tbody>
                </table>
                    <div class="container-fluid position-fixed py-4 bg-white" style="bottom: 0; border-top: 1px solid #DDD;">
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-3 mt-3">
                                <h5 class="sgi-content-title">
                                <span class="font-weight-bold">Patrimônio R$ {{ number_format($patrimonyTotal, 2, ',', '.') }}</span>
                                </h5>
                            </div>
                            <div class="col-md-3 mt-3">
                                <h5 class="sgi-content-title">
                                    <span class="font-weight-bold">Premiação R$ {{ number_format($awardTotal, 2, ',', '.') }}</span>
                                </h5>
                            </div>
                            <div class="col-md-3 mt-3">
                                <h5 class="sgi-content-title">
                                    <span class="font-weight-bold">Saldo R$ {{ number_format($saleTotal, 2, ',', '.') }}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 d-flex justify-content-center">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/select2-bootstrap4.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('/js/cash_flows/index-cash-flow.js') }}"></script>
@endpush
