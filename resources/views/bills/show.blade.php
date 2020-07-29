@extends('layouts.admin')
@section('title', "Valor a pagar R$ {$bill->bill_value_formatted}")
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-3 rounded-0 "><i class="fas fa-bars"></i></button>
                <h1 class="font-weight-bold sgi-content-title">Valor a pagar R$ {{ $bill->bill_value_formatted }} </h1>
                <a class="btn btn-primary ml-auto mt-2 mr-1" href="{{ route('admin.home') }}">
                    <i class="fas fa-home"></i> Voltar a home
                </a>
                <a href="{{ route('admin.financial.bills.edit', [ 'id' => $bill->id ]) }}" class="btn btn-primary mt-2 mr-1">
                    <i aria-hidden="true" class="fas fa-edit"></i> Editar
                </a>
                <form id="form_delete" class="d-inline" action="{{ route('admin.financial.bills.destroy', [ 'id' => $bill->id ]) }}" method="post">
                    @csrf
                    <button id="btn_delete" data-placement="top" class="btn btn-danger mt-2">
                        <i class="fas fa-times"></i> Remover
                    </button>
                </form>
            </header>
            <div class="container-fluid mt-2">
                <div class="row p-3">
                    <label class="font-weight-bold">Fornecedor</label>
                    <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $bill->provider_name_formatted }}
                    </div>
                </div>

                <div class="row p-3">
                    <label class="font-weight-bold">Data de pagamento</label>
                    <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $bill->bill_payday_formatted }}
                    </div>
                </div>

                <div class="row p-3">
                    <label class="font-weight-bold">Data de vencimento</label>
                    <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $bill->bill_due_date_formatted }}
                    </div>
                </div>

                <div class="row p-3">
                    <label class="font-weight-bold">Conta de débito</label>
                    <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        BANCO {{ $bill->bank_name }} | AG {{ $bill->bank_agency }} | CONTA {{ $bill->bank_account }}
                    </div>
                </div>

                <div class="row p-3">
                    <label class="font-weight-bold">Criado em</label>
                    <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $bill->created_at }}
                    </div>
                </div>
            <div>

        </div>
    </div>
</div>
@endsection
