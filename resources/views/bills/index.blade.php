@extends('layouts.admin')
@section('content')
@section('title', 'Contas a pagar')
<div class="container-fluid">
        <div class="row shadow bg-white rounded">
            @include('components.leftbar')
                <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
                    @include('components.header_content', [
                        'title' => 'Contas a pagar',
                        'buttonTitle' => 'Nova conta a pagar',
                        'route' => 'admin.financial.bills.create',
                        'icon' => 'fas fa-plus'
                    ])

                @include('components.message')

                <div class="col-lg-12 mt-4 d-flex flex-nowrap mb-2">

                    <form class="col-md-12 d-flex p-0" action="" method="get">
                        <label class="font-weight-bold mt-2 mr-2" for="bill_in">De </label>
                        <input class="form-control text-uppercase" type="date" value="{{ old('bill_in', \Request::get('bill_in')) }}" name="bill_in" id="bill_in" />

                        <label class="font-weight-bold mt-2 ml-2 mr-2" for="bill_until">Até </label>
                        <input class="form-control text-uppercase" type="date" value="{{ old('bill_until', \Request::get('bill_until')) }}" name="bill_until" id="bill_until" />

                        <select class="form-control ml-2 mr-2" name="bill_status" id="bill_status">
                            <option value="">STATUS</option>
                            <option {{ \Request::get('bill_status') == 1 ? 'selected' : '' }} value="1">PAGO</option>
                            <option {{ \Request::get('bill_status') == 2 ? 'selected' : '' }} value="2">PENDENTE</option>
                        </select>

                        <select class="form-control" name="bill_provider" id="bill_provider">
                            <option value="">SELECIONAR FORNECEDOR</option>
                            @foreach ($providers as $provider)
                                <option class="text-uppercase" {{ \Request::get('bill_provider') == $provider->id ? 'selected' : '' }} value="{{ $provider->id }}">{{ $provider->provider_name }}</option>
                            @endforeach
                        </select>

                        <button id="btn-date" class="btn btn-primary mr-2 ml-2" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <table id="client_table" class="table table-sm table-striped table-hover" style="margin-bottom:15%;">
                    <thead>
                        <tr>
                            <th scope="col">Fornecedor</th>
                            <th class="sgi-show-or-not" scope="col">Valor</th>
                            <th scope="col">Data de pag.</th>
                            <th scope="col">Data de venc.</th>
                            <th class="sgi-show-or-not" scope="col">Conta de débito</th>
                            <th class="sgi-show-or-not" scope="col">Status</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bills as $bill)
                            <tr>
                                <td class="text-uppercase">{{ $bill->provider_name_formatted }}</td>
                                <td class="sgi-show-or-not">R$ {{ $bill->bill_value_formatted }}</td>
                                <td>{{ $bill->bill_payday_formatted }}</td>
                                <td>{{ $bill->bill_due_date_formatted }}</td>
                                <td class="text-uppercase sgi-show-or-not">{{ $bill->bill_bank_name_agency_account_formatted }}</td>
                                <td class="sgi-show-or-not text-uppercase">{{ $bill->bill_payment_status == 1 ? 'Pago' : 'Pendente' }}</td>
                                <td>
                                    <a data-toggle="tooltip" data-placement="top" title="Visualizar" class="btn btn-sm btn-primary" href="{{ route('admin.financial.bills.show', ['id' => $bill->id]) }}">
                                        <i class="far fa-eye"></i>
                                    </a>
                                    <a data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-sm btn-primary" href="{{ route('admin.financial.bills.edit', ['id' => $bill->id]) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if ($bill->bill_payment_status != 1)
                                        <form class="d-inline sgi_form_delete" action="{{ route('admin.financial.bills.destroy', [ 'id' => $bill->id ]) }}" method="post">
                                            @csrf
                                            <button id="btn_delete" data-toggle="tooltip" data-placement="top" title="Remover" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <td class="text-center" colspan="8"><i class="fas fa-frown"></i> Nenhuma conta a pagar ainda registrada...</td>
                        @endforelse
                    </tbody>
                </table>
                    <div class="col-lg-12 d-flex justify-content-center">{!! $bills->links() !!}</div>
                    <div class="container-fluid position-fixed py-4 bg-white" style="bottom: 0; border-top: 1px solid #DDD;">
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-3 mt-3">
                                <h4 class="sgi-content-title">
                                <span class="font-weight-bold">Total R$ {{ $billTotal }}</span>
                                </h4>
                            </div>
                        </div>
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
    <script src="{{ asset('/js/bills/index-bill.js') }}"></script>
@endpush
