<input type="hidden" name="awarded_type" value="3">

<div class="form-group mt-3 mb-3">
    <label for="awarded_value" class="font-weight-bold">
        Valor de premiação
        <span class="sgi-forced">*</span>
    </label>
    <input data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," value="{{ old('awarded_value', $award->awarded_value_formatted ?? null) }}" type="text" id="awarded_value" name="awarded_value" placeholder="Valor (R$)" class="form-control sgi-border-2">
</div>

<div class="form-group mt-2">
    <label for="awarded_bank_id" class="font-weight-bold">
        Banco <span class="sgi-forced">*</span>
    </label>

    <select name="awarded_bank_id" id="awarded_bank_id" class="form-control sgi-border-2 sgi-select2">
        <option value="">SELECIONAR BANCO</option>
        @php
            $awardedBankId = $award->awarded_bank_id ?? null;
        @endphp
            @foreach ($banks as $bank)
                <option {{ $bank->id == $awardedBankId ? 'selected' : '' }} value="{{ $bank->id }}">BANCO {{ $bank->bank_name }} | AG {{ $bank->bank_agency }} | CONTA {{ $bank->bank_account }}</option>
            @endforeach
        </option>
    </select>
</div>

<div class="form-group mt-2">
    <label for="awarded_bank_id" class="font-weight-bold">
        Status <span class="sgi-forced">*</span>
    </label>

    @php
        $status = $award->awarded_status ?? null;
    @endphp

    <select name="awarded_status" id="awarded_status" class="form-control sgi-border-2">
        <option value="">SELECIONAR STATUS DE PAGAMENTO</option>
        <option {{ $status == 1 ? 'selected' : '' }} value="1">PAGO</option>
        <option {{ $status == 4 ? 'selected' : '' }} value="4">CANCELAR</option>
    </select>
</div>

<div class="form-group">
    <label class="font-weight-bold" for="awarded_date_payment_manual">
        Data de pagamento manual
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control sgi-border-2" type="date" value="{{ date('Y-m-d') }}" name="awarded_date_payment_manual" id="awarded_date_payment_manual" />
</div>
