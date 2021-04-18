@extends('layouts.admin')
@section('content')
@section('title', 'Fluxo de caixa')

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
                                <td>{{ $cashFlow->drawer }}</td>
                                <td>{{ $cashFlow->document }}</td>
                                <td>{{ $cashFlow->bank  }}</td>
                                <td>{{ $cashFlow->flow_movement_date_formatted }}</td>
                                @php
                                    $creditPatrimonyMoney = $cashFlow->credit_patrimony_value_money;
                                    $debitPatrimonyMoney = $cashFlow->debit_patrimony_value_money
                                @endphp
                                <td>R$ {{ $creditPatrimonyMoney ?? $debitPatrimonyMoney ?? 0 }}</td>
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
