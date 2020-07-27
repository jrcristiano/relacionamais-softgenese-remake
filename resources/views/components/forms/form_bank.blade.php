@include('components.forms.errors.error')
@csrf
<div class="form-group">
    <label class="font-weight-bold" for="bank_name">
        Nome do banco
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('bank_name', $bank->bank_name ?? null) }}" type="text" id="bank_name" name="bank_name" placeholder="Banco" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="bank_agency">
        Agência
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('bank_agency', $bank->bank_agency ?? null) }}" type="text" id="bank_agency" name="bank_agency" placeholder="Agência" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="bank_account">
        Conta
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('bank_account', $bank->bank_account ?? null) }}" type="text" id="bank_account" name="bank_account" placeholder="Conta" />
</div>
