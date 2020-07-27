@include('components.forms.errors.error')
@csrf

<div class="form-group mt-3">
    <label class="font-weight-bold" for="receive_award_real_value">
        Valor real de premiação
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control sgi-border-2" value="{{ old('receive_award_real_value', $receive->receive_award_real_value ?? null) }}" type="text" id="receive_award_real_value" name="receive_award_real_value" placeholder="Valor real de premiação (R$)" />
</div>

<div class="form-group mt-4">
    <label class="font-weight-bold" for="receive_taxable_real_value">
        Valor real tributável
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control sgi-border-2" value="{{ old('receive_taxable_real_value', $receive->receive_taxable_real_value ?? null) }}" type="text" id="receive_taxable_real_value" name="receive_taxable_real_value" placeholder="Valor real tributável (R$)" />
</div>

<div class="form-group mt-4">
    <label class="font-weight-bold" for="receive_date_receipt">
        Data de recebimento
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control sgi-border-2" value="{{ old('receive_date_receipt', $receive->receive_date_receipt ?? date('Y-m-d')) }}" type="date" id="receive_date_receipt" name="receive_date_receipt" />
</div>

<div class="form-group mt-4 py-1">
    <label class="font-weight-bold" for="receive_status">
        Status de recebimento
        <span class="sgi-forced">*</span>
    </label>
    <select class="form-control sgi-border-2" name="receive_status" id="receive_status">
        <option selected value="">Selecionar status de recebimento</option>
        @php
            $status = $receive->receive_status ?? null;
        @endphp
        <option {{ $status == '1' ? 'selected' : '' }} value="1">Pago</option>
        <option {{ $status == '2' ? 'selected' : '' }} value="2">Pendente</option>
    </select>
</div>
