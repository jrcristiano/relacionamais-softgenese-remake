@extends('layouts.admin')
@section('title', 'Pagamento manual')
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-3 rounded-0 "><i class="fas fa-bars"></i></button>
                <h1 class="font-weight-bold sgi-content-title text-uppercase">Pagamento manual</h1>
                <a class="btn btn-primary ml-auto mt-2 mr-1" href="{{ url()->previous() }}">
                    <i class="fas fa-undo"></i> Voltar
                </a>
                <a href="{{ route('admin.register.awardeds.edit', [ 'id' => $paymentManual->id ]) }}" class="btn btn-primary mt-2 mr-1">
                    <i aria-hidden="true" class="fas fa-edit"></i> Editar
                </a>
            </header>
            <div class="container-fluid mt-2">
                <div class="row p-2">
                    <label class="font-weight-bold">Valor</label>
                    <div class="col-md-12 sgi-border-2 py-2 px-3" style="border-radius: 0.25rem;">
                        {{ $paymentManual->awarded_value_formatted }}
                    </div>
                </div>

                <div class="row p-2">
                    <label class="font-weight-bold">Banco</label>
                    <div class="col-md-12 sgi-border-2 py-2 px-3" style="border-radius: 0.25rem;">
                        BANCO {{ $bank->bank_name }} | AG {{ $bank->bank_agency }} | CONTA {{ $bank->bank_account }}
                    </div>
                </div>

                <div class="row p-2">
                    <label class="font-weight-bold">Status</label>
                    <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $paymentManual->awarded_status == 1 ? 'PAGO' : 'CANCELADO' }}
                    </div>
                </div>

                <div class="row p-2">
                    <label class="font-weight-bold">Criado em</label>
                    <div class="col-md-12 sgi-border-2 py-2 px-3" style="border-radius: 0.25rem;">
                       {{ $paymentManual->created_at_formatted }}
                    </div>
                </div>
            <div>

        </div>
    </div>
</div>
@endsection
