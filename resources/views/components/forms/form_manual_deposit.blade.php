@include('components.forms.errors.error')
@csrf
<div class="form-group mt-4">
    <label class="font-weight-bold" for="awarded_value">
        Valor de premiação
        <span class="sgi-forced">*</span>
    </label>
    <input data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," class="form-control sgi-border-2" value="{{ number_format($award->awarded_value, 2, ',', '.') }}" type="text" id="awarded_value" name="awarded_value" placeholder="Valor (R$)" />
</div>

@php
    $awardedType = $award->awarded_type ?? null;
@endphp

@if (!$id)
    <div class="form-group mt-2">
        <label class="font-weight-bold" for="awarded_type">
            Tipo de premiação
            <span class="sgi-forced">*</span>
        </label>
        <select id="awarded_type" class="form-control" style="border: 2px solid #ced4da;" name="awarded_type">
            <option {{ $awardedType == null ? 'selected' : ''  }} value="">Selecionar tipo de premiação</option>
            <option {{ $awardedType == 1 ? 'selected' : ''  }} value="1">Cartão</option>
            <option {{ $awardedType == 2 ? 'selected' : ''  }} value="2">Depósito em conta</option>
            <option {{ $awardedType == 3 ? 'selected' : ''  }} value="3">Depósito manual</option>
        </select>
    </div>
@else
    <input value="{{ $awardedType }}" type="hidden" name="awarded_type">
@endif

<div class="form-group mt-2">
    <label class="font-weight-bold" for="awarded_bank_id">
        Banco
        <span class="sgi-forced">*</span>
    </label>
    <select class="form-control sgi-border-2 sgi-select2" name="awarded_bank_id" id="awarded_bank_id">
        <option value="{{ $bank->id ?? '' }}">{{ $bank->bank_account ?? 'Selecionar banco' }}</option>

        @foreach ($banks as $bank)
            @php
                $bankId = $bank->id ?? '';
                $bankName = $bank->bank_name ?? '';
                $bankAccount = $bank->bank_account ?? '';
                $bankAgency = $bank->bank_agency ?? '';
            @endphp
            @php
                $awardedBankId = $award->awarded_bank_id ?? null;
            @endphp
            <option {{ $bank->id == $awardedBankId ? 'selected' : '' }} value="{{ $bankId }}">{{ $bankName }} | AG {{ $bankAgency }} | Conta {{ $bankAccount }}</option>
            @php
                $bankHidden = $bankName;
            @endphp
        @endforeach
    </select>

    <input type="hidden" value="{{ \Request::get('pedido_id') }}" name="awarded_demand_id">
    <input type="hidden" name="manual_deposit_id" value="{{ $id }}">
</div>

<div class="form-group">
    <label class="font-weight-bold" for="awarded_status_manual">
        Status de premiação
        <span class="sgi-forced">*</span>
    </label>
    <select class="form-control sgi-border-2" name="awarded_status_manual" id="awarded_status_manual">
        <option selected value="">Selecionar status</option>
        @php
            $awardedStatus = $award->awarded_status_manual ?? null;
        @endphp
        <option {{ $awardedStatus == '1' || $awardedStatus == null ? 'selected' : '' }} value="1">Pago</option>
        @if ($award->awarded_status == 1)
            <option {{ $awardedStatus == 4 ? 'selected' : '' }} value="4">Cancelar</option>
        @else
            <option {{ $awardedStatus == 3 ? 'selected' : '' }} value="3">Pendente</option>
            <option {{ $awardedStatus == 4 ? 'selected' : '' }} value="4">Cancelar</option>
        @endif
    </select>
</div>

<div class="form-group awarded_date_payment_manual">
    <label class="font-weight-bold" for="awarded_date_payment_manual">
        Data de pagamento manual
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control sgi-border-2" type="date" value="{{ $award->awarded_date_payment_manual ? $award->awarded_date_payment_manual : date('Y-m-d') }}" name="awarded_date_payment_manual" id="awarded_date_payment_manual" />
</div>

@push('scripts')
    <script src="{{ asset('/js/manual-deposits/create-edit-manual.js') }}"></script>
@endpush
