@include('components.forms.errors.error')
@csrf
<div class="form-group">
    <label class="font-weight-bold" for="transfer_account_debit">
        Conta a debitar
        <span class="sgi-forced">*</span>
    </label>
    <select class="form-control sgi-border-2" name="transfer_account_debit" id="transfer_account_debit">
        <option value="">SELECIONAR CONTA A DEBITAR</option>
        @foreach ($banks as $bank)
        @php
            if (isset($transfer)) {
                $selected = $transfer->transfer_account_debit == $bank->id ? 'selected' : '';
            }
        @endphp
            <option class="text-uppercase" {{ $selected ?? null }} value="{{ $bank->id }}">BANCO {{ $bank->bank_name }} | AG {{ $bank->bank_agency }} | CONTA {{ $bank->bank_account }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label class="font-weight-bold" for="transfer_account_credit">
        Conta a creditar
        <span class="sgi-forced">*</span>
    </label>
    <select class="form-control sgi-border-2" name="transfer_account_credit" id="transfer_account_credit">
        <option value="">SELECIONAR CONTA A CREDITAR</option>
        @foreach ($banks as $bank)
        @php
            if (isset($transfer)) {
                $selected = $transfer->transfer_account_credit == $bank->id ? 'selected' : '';
            }
        @endphp
            <option class="text-uppercase" {{ $selected ?? null }} value="{{ $bank->id }}">BANCO {{ $bank->bank_name }} | AG {{ $bank->bank_agency }} | CONTA {{ $bank->bank_account }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label class="font-weight-bold" for="transfer_value">
        Valor da transferência
        <span class="sgi-forced">*</span>
    </label>
    @php
        $transferValue = $transfer->transfer_value_formatted ?? null;
    @endphp
    <input data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," class="form-control text-uppercase sgi-border-2" value="{{ old('transfer_value', $transferValue) }}" type="text" id="transfer_value" name="transfer_value" placeholder="Valor" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="transfer_date">
        Data
        <span class="sgi-forced">*</span>
    </label>
    @php
        $transferValue = $transfer->transfer_value ?? null;
    @endphp
    <input class="form-control sgi-border-2 text-uppercase" type="date" class="form-control sgi-border-2" value="{{ $transfer->created_at_formatted ?? date('Y-m-d') }}" id="transfer_date" name="transfer_date" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="transfer_type">
        Tipo de transferência <span class="sgi-forced">*</span>
    </label>
    @php
        $transferType = $transfer->transfer_type ?? null;
    @endphp
    <select class="form-control sgi-border-2" name="transfer_type" id="transfer_type">
        <option value="">SELECIONAR TIPO DE TRANSFERÊNCIA</option>
        <option {{ $transferType == 1 ? 'selected' : '' }} value="1">PATRIMÔNIO</option>
        <option {{ $transferType == 2 ? 'selected' : '' }} value="2">PREMIAÇÃO</option>
    </select>
</div>
