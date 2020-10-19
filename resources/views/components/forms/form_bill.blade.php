@include('components.forms.errors.error')
@csrf

@php
    // dd($bill);
@endphp

<div class="form-group">
    <label class="font-weight-bold" for="bill_value">
        Valor da conta
        <span class="sgi-forced">*</span>
    </label>
    <input required="required" data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," class="form-control text-uppercase sgi-border-2" value="{{ old('bill_value', $bill->bill_value_formatted ?? null) }}" type="text" id="bill_value" name="bill_value" placeholder="Valor da conta" />
</div>

<div class="form-group mt-4">
    <label class="font-weight-bold" for="bill_payday">
        Data de pagamento
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('bill_payday', $bill->bill_payday ?? null) }}" type="date" id="bill_payday" name="bill_payday" />
</div>

<div class="form-group mt-4">
    <label class="font-weight-bold" for="bill_due_date">
        Data de vencimento
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('bill_due_date', $bill->bill_due_date ?? null) }}" type="date" id="bill_due_date" name="bill_due_date" />
</div>

<div class="form-group mt-4 py-1">
    <label class="font-weight-bold" for="bill_provider_id">
        Fornecedor
        <span class="sgi-forced">*</span>
    </label>
    <select class="form-control sgi-border-2 sgi-select2" name="bill_provider_id" id="bill_provider_id">
        <option class="text-uppercase" value="{{ $providers[0]->id ?? '' }}">{{ $provider->provider_name ?? 'SELECIONAR FORNECEDOR' }}</option>

        @foreach ($providers as $provider)
            @php
                $billProviderId = $bill->bill_provider_id ?? null;
            @endphp
            <option class="text-uppercase" {{ $billProviderId == $provider->id ? 'selected' : '' }} value="{{ $provider->id ?? null }}">{{ $provider->provider_name ?? null }}</option>
        @endforeach
    </select>
</div>

<div class="form-group mt-4 py-1">
    <label class="font-weight-bold" for="bill_bank_id">
        Conta de débito
        <span class="sgi-forced">*</span>
    </label>
    <select class="form-control text-uppercase text-uppercase sgi-border-2 sgi-select2" name="bill_bank_id" id="bill_bank_id">
        <option class="text-uppercase" value="{{ $bank->id ?? '' }}">{{ $bank->bank_account ?? 'SELECIONAR CONTA DE DÉBITO' }}</option>

        @foreach ($banks as $bank)
            @php
                $bankId = $bank->id ?? '';
                $bankName = $bank->bank_name ?? '';
                $bankAccount = $bank->bank_account ?? '';
                $bankAgency = $bank->bank_agency ?? '';
            @endphp
            @php
                $billBankId = $bill->bill_bank_id ?? null;
            @endphp
            <option class="text-uppercase" {{ $bank->id == $billBankId ? 'selected' : '' }} value="{{ $bankId }}">BANCO {{ $bankName }} | AG {{ $bankAgency }} | CONTA {{ $bankAccount }}</option>
            @php
                $bankHidden = $bankName;
            @endphp
        @endforeach
    </select>
</div>

@php
    $id = $id ?? null;
@endphp

<div class="form-group mt-4 py-1">
    <label class="font-weight-bold" for="bill_payment_status">
        Status de pagamento
        <span class="sgi-forced">*</span>
    </label>
    <select class="form-control text-uppercase sgi-border-2" name="bill_payment_status" id="bill_payment_status">
        <option class="text-uppercase" selected value="">SELECIONAR STATUS DE PAGAMENTO</option>
        @php
            $billPaymentStatus = ($bill->bill_payment_status ?? null);
        @endphp
        <option class="text-uppercase" {{ $billPaymentStatus == 1 ? 'selected' : '' }} value="1">PAGO</option>
        <option class="text-uppercase" {{ $billPaymentStatus == 2 ? 'selected' : '' }} value="2">PENDENTE</option>
    </select>
</div>

<div class="form-group">
    <label class="font-weight-bold" for="bill_description">
        Descrição
        <span class="sgi-forced">*</span>
    </label>
    <input required="required" class="form-control text-uppercase sgi-border-2" value="{{ old('bill_description', $bill->bill_description ?? null) }}" type="text" id="bill_description" name="bill_description" placeholder="Descrição" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="exampleFormControlTextarea1">Observações</label>
    <textarea name="bill_note" class="form-control text-uppercase sgi-border-2" placeholder="Observações da conta" id="exampleFormControlTextarea1" rows="4">{{ old('bill_note', $bill->bill_note ?? null) }}</textarea>
</div>

@push('scripts')
    <script src="{{ asset('/js/bills/create-edit-bill.js') }}"></script>
@endpush
