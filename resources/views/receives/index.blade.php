@extends('layouts.admin')
@section('title', 'Contas a receber')
@section('content')
@php
    // dd($receives)
@endphp
<div class="container-fluid">
    <div class="row shadow bg-white rounded">

        @include('components.leftbar')

        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            @include('components.header_content', [
                'title' => 'Contas a receber',
                'buttonTitle' => 'Voltar a home',
                'route' => 'admin.home',
                'icon' => 'fas fa-home'
            ])

            @include('components.message')

            <div class="col-lg-12 mt-3 d-flex flex-nowrap mb-2 sgi-sub-content-header">
                <form class="col-md-12 d-flex p-0" action="" method="get">
                    <label class="font-weight-bold mt-2 mr-2" for="receive_in">De </label>
                    <input class="form-control" type="date" value="{{ old('receive_in', \Request::get('receive_in')) }}" name="receive_in" id="receive_in" />

                    <label class="font-weight-bold mt-2 ml-2 mr-2" for="receive_until">Até </label>
                    <input class="form-control" type="date" value="{{ old('receive_until', \Request::get('receive_until')) }}" name="receive_until" id="receive_until" />

                    <select class="form-control ml-2 mr-2" name="receive_status" id="receive_status">
                        <option value="">STATUS</option>
                        <option {{ \Request::get('receive_status') == 1 ? 'selected' : '' }} value="1">ABERTO</option>
                        <option {{ \Request::get('receive_status') == 2 ? 'selected' : '' }} value="2">RECEBIDO</option>
                        <option {{ \Request::get('receive_status') == 3 ? 'selected' : '' }} value="3">CANCELADO</option>
                    </select>

                    <select class="form-control mr-2" name="receive_client" id="receive_client">
                        <option value="">SELECIONAR CLIENTE POR NOME</option>
                        @foreach ($clients as $client)
                        <option {{ \Request::get('receive_client') == $client->client_company ? 'selected' : '' }} value="{{ $client->client_company }}">{{ $client->client_company }}</option>
                        @endforeach
                    </select>

                    <button id="btn-date" class="btn btn-primary mr-2" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <table id="client_table" class="table table-sm table-striped table-hover mt-3" style="margin-bottom:15%;">
                <thead>
                    <tr>
                        <th scope="col">Cliente</th>
                        <th scope="col">Nota fiscal</th>
                        <th scope="col">Status</th>
                        <th scope="col">Vencimento</th>
                        <th scope="col">Emitido em</th>
                        <th scope="col">Premiação</th>
                        <th scope="col">Patrimônio</th>
                        <th scope="col">Outros val.</th>
                        <th scope="col">Data de recebim.</th>
                        <th scope="col">Ação</th>
                        <th scope="col">Total</th>
                    </tr>
                    </thead>
                        <tbody>
                            @forelse ($receives as $receive)
                                <tr>
                                    <td class="text-uppercase" scope="row">{{ $receive->demand_client_name_formatted }}</td>
                                    <td scope="row">{{ $receive->note_number }}</td>
                                    <td class="text-uppercase" scope="row">{{ $receive->note_status_formatted }}</td>
                                    <td scope="row">{{ $receive->note_due_date_formatted }}</td>
                                    <td scope="row">{{ $receive->note_created_at_formatted }}</td>
                                    <td scope="row">R$ {{ $receive->receive_prize_amount_formatted }}</td>
                                    @php
                                        $patrimonyColumn = $receive->receive_taxable_amount + $receive->demand_taxable_manual;
                                    @endphp
                                    <td scope="row">R$ {{ number_format($patrimonyColumn, 2, ',', '.') }}</td>
                                    <td scope="row">R$ {{ number_format($receive->demand_other_value, 2, ',', '.') }}</td>
                                    <td class="text-uppercase" scope="row">{{ $receive->note_receipt_date_formatted }}</td>

                                    @php
                                        $route = route('admin.financial.notes.edit', ['id' => $receive->note_id, 'pedido_id' => $receive->note_demand_id]);
                                        $icon = '<i class="far fa-edit"></i>';
                                        $generateReceipt = "<a data-toggle='tooltip' data-placement='top' data-original-title='Editar nota' class='btn btn-primary btn-sm' href='$route'>{$icon}</a>";
                                    @endphp

                                    <td>{!! $receive->bank_account ?? $generateReceipt !!}</td>
                                    @php
                                        $prizeAmount = (float) $receive->receive_prize_amount;
                                        $taxableAmount = (float) $receive->receive_taxable_amount;
                                        $taxableManual = (float) $receive->demand_taxable_manual;
                                        $otherValues = (float) $receive->demand_other_value;

                                        $total = $prizeAmount + $taxableAmount + $otherValues + $taxableManual;
                                    @endphp
                                    <th>R$ {{ takeMoneyFormat($total) }}</th>
                                </tr>
                            @empty
                                <td colspan="11" class="text-center"><i class="fas fa-frown"></i> Nenhuma conta a receber ainda registrada...</td>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="container-fluid position-fixed py-4 mt-5 bg-white" style="bottom: 0; border-top: 1px solid #DDD;">
                        <div class="row">
                            <div class="col-lg-10">
                                @if ($receives->count() >= 500)
                                    {!!
                                        $receives->appends([
                                            'receive_in' => \Request::get('receive_in'),
                                            'receive_until' => \Request::get('receive_until'),
                                            'receive_status' => \Request::get('receive_status')
                                        ])
                                        ->links()
                                    !!}
                                @endif
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-3 mt-3">
                                <h5 class="sgi-content-title">
                                    @php
                                        $receivePatrimonyTotal = $patrimonyTotal->patrimony_total + $otherValueTotal->other_value_total + $demandTaxableManual->taxable_manual;
                                    @endphp
                                <span class="font-weight-bold">Patrimônio R$ {{ number_format($receivePatrimonyTotal, 2, ',', '.') }}</span>
                                </h5>
                            </div>
                            <div class="col-md-3 mt-3">
                                <h5 class="sgi-content-title">
                                    <span class="font-weight-bold">Premiação R$ {{ $awardTotal->award_total_formatted }}</span>
                                </h5>
                            </div>
                            <div class="col-md-3 mt-3">
                                <h5 class="sgi-content-title">
                                    <span class="font-weight-bold">Total R$ {{ number_format($saleTotal, 2, ',', '.') }}</span>
                                </h5>
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
<script src="{{ asset('/js/receives/index-receive.js') }}"></script>
@endpush
