@include('components.forms.errors.error')
@csrf

<input type="hidden" name="awarded_demand_id" value="{{ \Request::get('pedido_id') }}">
@php
    $id = $id ?? null;
    $spreadsheet = $spreadsheetExists->awarded_upload_table ?? null;
@endphp
@php
    $awardedType = $award->awarded_type ?? null;
@endphp
@if (!$id)
    <div class="form-group">
        <label class="font-weight-bold" for="awarded_type">
            Tipo de premiação
            <span class="sgi-forced">*</span>
        </label>
        <select id="awarded_type" class="form-control" style="border: 2px solid #ced4da;" name="awarded_type">
            <option {{ $awardedType == null ? 'selected' : ''  }} value="">SELECIONAR TIPO DE PREMIAÇÃO</option>
            <option {{ $awardedType == 1 ? 'selected' : ''  }} value="1">CARTÃO</option>
            <option {{ $awardedType == 2 ? 'selected' : ''  }} value="2">DEPÓSITO EM CONTA</option>
            <option {{ $awardedType == 3 ? 'selected' : ''  }} value="3">PAGAMENTO MANUAL</option>
        </select>
    </div>
@else
    <input value="{{ $awardedType }}" type="hidden" name="awarded_type">
@endif

@if (!$id && !$spreadsheet)
    <div class="form-group upload-file d-none">
        <label class="font-weight-bold" for="awarded_upload_table">
            Importar arquivo
            <span class="sgi-forced">*</span>
        </label>
        <input class="btn cursor-pointer form-control sgi-border-dashed pt-3 pb-5" value="{{ old('awarded_upload_table', $award->awarded_upload_table ?? null) }}" type="file" id="awarded_upload_table" name="awarded_upload_table" />
    </div>
@endif

<div id="card_type" class="form-group d-none">
    <label class="font-weight-bold" for="awarded_type_card">
        Tipo de cartão
        <span class="sgi-forced">*</span>
    </label>
    @php
        $cardType = $award->awarded_card_type ?? null;
    @endphp
    <select disabled id="awarded_type_card" class="form-control" style="border: 2px solid #ced4da;" name="awarded_type_card">
    <option value="">SELECIONAR TIPO DE CARTÃO</option>
        <option {{ $cardType == '1' ? 'selected' : ''  }} value="1">CARTÃO BLUE</option>
        <option {{ $cardType == '2' ? 'selected' : ''  }} value="2">CARTÃO GREEN</option>
    </select>
</div>
    @php
        $awardedStatus = $award->awarded_status ?? null;
    @endphp

<div class="discover_bank_status d-none">
    <div class="form-group mt-3 mb-3 d-none payment_manual_value">
        <label class="font-weight-bold" for="awarded_value">
            Valor de premiação
            <span class="sgi-forced">*</span>
        </label>
        <input data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," class="form-control payment_value sgi-border-2" value="{{ old('awarded_value', $award->awarded_value ?? null) }}" type="text" id="awarded_value" name="awarded_value" placeholder="Valor (R$)" />
    </div>

    <div class="form-group mt-2">
        <label class="font-weight-bold" for="awarded_bank_id">
            Banco
            <span class="sgi-forced">*</span>
        </label>
        <select class="form-control sgi-border-2 sgi-select2" name="awarded_bank_id" id="awarded_bank_id">
            <option value="{{ $bank->id ?? '' }}">{{ $bank->bank_account ?? 'SELECIONAR BANCO' }}</option>

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
                <option class="text-uppercase" {{ $bank->id == $awardedBankId ? 'selected' : '' }} value="{{ $bankId }}">{{ $bankName }} | AG {{ $bankAgency }} | Conta {{ $bankAccount }}</option>
                @php
                    $bankHidden = $bankName;
                @endphp
            @endforeach
        </select>
    </div>
</div>

<div class="form-group {{ $id ? '' : 'd-none' }} mt-3 status_group">
    <label class="font-weight-bold" for="awarded_status">
        Status
        <span class="sgi-forced">*</span>
    </label>
    <select id="awarded_status" class="form-control status_deposit_account {{ $id ? '' : 'd-none' }}" style="border: 2px solid #ced4da;" name="awarded_status_deposit">
        <option value="">SELECIONAR STATUS DE PREMIAÇÃO</option>
        @if ($id)
            <option {{ $awardedStatus == 1 ? 'selected' : '' }} value="1">ENVIAR PARA REMESSA</option>
            <option {{ $awardedStatus == 2 ? 'selected' : '' }} value="2">AGUARDANDO PAGAMENTO</option>
        @endif
        @if (!$id)
            <option selected value="3">PENDENTE</option>
        @endif
    </select>

    <select class="form-control sgi-border-2 status_payment_manual d-none" name="awarded_status_manual" id="awarded_status">
        <option value="">SELECIONAR STATUS</option>
        @php
            $awardedStatus = $award->awarded_status_manual ?? null;
        @endphp
        <option {{ $awardedStatus == '1' ? 'selected' : '' }} value="1">PAGO</option>
        <option {{ $awardedStatus == '4' ? 'selected' : '' }} value="4">CANCELAR</option>
    </select>
</div>

<div class="form-group d-none awarded_date_payment_manual">
    <label class="font-weight-bold" for="awarded_date_payment_manual">
        Data de pagamento manual
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control sgi-border-2" type="date" value="{{ date('Y-m-d') }}" name="awarded_date_payment_manual" id="awarded_date_payment_manual" />
</div>

@push('scripts')
    <script src="{{ asset('/js/awards/create-edit-award.js') }}"></script>
@endpush
