@include('components.forms.errors.error')
@csrf

<div class="form-group">
    <input required="required" class="form-control sgi-border-2" value="{{ old('spreadsheet_name', $spreadsheet->spreadsheet_name ?? null) }}" type="hidden" id="spreadsheet_name" name="spreadsheet_name" placeholder="Nome" />
</div>

<div class="form-group">
    <input required="required" class="form-control sgi-border-2" value="{{ old('spreadsheet_document', $spreadsheet->spreadsheet_document ?? null) }}" type="hidden" id="spreadsheet_document" name="spreadsheet_document" placeholder="Documento" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="spreadsheet_value">
        Valor
        <span class="sgi-forced">*</span>
    </label>
    <input required="required" class="form-control sgi-border-2" data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," value="{{ old('spreadsheet_value', $spreadsheet->spreadsheet_value ?? null) }}" type="text" id="spreadsheet_value" name="spreadsheet_value" placeholder="Valor" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="spreadsheet_bank">
        Banco
        <span class="sgi-forced">*</span>
    </label>
    <input required="required" class="form-control sgi-border-2" value="{{ old('spreadsheet_bank', $spreadsheet->spreadsheet_bank ?? null) }}" type="text" id="spreadsheet_bank" name="spreadsheet_bank" placeholder="Banco" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="spreadsheet_agency">
        Agência
        <span class="sgi-forced">*</span>
    </label>
    <input required="required" class="form-control sgi-border-2" value="{{ old('spreadsheet_agency', $spreadsheet->spreadsheet_agency ?? null) }}" type="text" id="spreadsheet_agency" name="spreadsheet_agency" placeholder="Agência" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="spreadsheet_account">
        Conta
        <span class="sgi-forced">*</span>
    </label>
    <input required="required" class="form-control sgi-border-2" value="{{ old('spreadsheet_account', $spreadsheet->spreadsheet_account ?? null) }}" type="text" id="spreadsheet_account" name="spreadsheet_account" placeholder="Conta" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="spreadsheet_account_type">
        Tipo de conta
        <span class="sgi-forced">*</span>
    </label>
    @php
        $accountType = $spreadsheet->spreadsheet_account_type ?? null;
    @endphp
    <select id="spreadsheet_account_type" class="form-control" style="border: 2px solid #ced4da;" name="spreadsheet_account_type">
    <option value="">Selecionar tipo de cartão</option>
        <option {{ $accountType == 'Conta corrente' ? 'selected' : ''  }} value="C">Conta corrente</option>
        <option {{ $accountType == 'Conta poupança' ? 'selected' : ''  }} value="P">Conta poupança</option>
    </select>
</div>

<div class="form-group">
    <input type="hidden" name="spreadsheet_award_id" value="{{ $awardId }}">
    <input type="hidden" name="spreadsheet_demand_id" value="{{ \Request::get('pedido_id') }}">
</div>

@push('scripts')
    <script src="{{ asset('/js/awards/create-edit-award.js') }}"></script>
@endpush
