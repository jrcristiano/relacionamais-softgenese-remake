@include('components.forms.errors.error')
@php
    // dd($noteReceipt)
@endphp
@csrf
<div class="sgi-template">
    <div class="form-group py-1">
        <label class="font-weight-bold" for="note_receipt_award_real_value">
            Selecionar campo
            <span class="sgi-forced">*</span>
        </label>
        @php
            $awardRealValue = $noteReceipt->note_receipt_award_real_value ?? null;
            $awardTaxableValue = $noteReceipt->note_receipt_taxable_real_value ?? null;
            $otherValues = $noteReceipt->note_receipt_other_value ?? null;
        @endphp
        <select class="form-control text-uppercase sgi-border-2 sgi-select2" name="select_field" id="select_field">
            <option class="text-uppercase" value="">Selecionar campo</option>
            <option class="text-uppercase" {{ $awardTaxableValue != 0 ? 'selected' : '' }} value="1">Patrimônio</option>
            <option class="text-uppercase" {{ $awardRealValue != 0 ? 'selected' : '' }} value="2">Valor de premiação</option>
            <option class="text-uppercase" {{ $otherValues != 0 ? 'selected' : '' }} value="3">Outros valores</option>
        </select>
    </div>

    <div class="form-group award_value py-1 {{ $awardRealValue != 0 ? '' : 'd-none' }}">
        <label class="font-weight-bold" for="note_receipt_award_real_value">
            Valor de premiação
        </label>
        @php

        @endphp
        <input value="{{ old('note_receipt_award_real_value', number_format($awardRealValue, 2, ',', '.')) }}" class="form-control text-uppercase sgi-border-2" data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," type="text" id="note_receipt_award_real_value" name="note_receipt_award_real_value" placeholder="Valor de premiação" />
    </div>

    <div class="form-group patrimony py-1 {{ $awardTaxableValue != 0 ? '' : 'd-none' }}">
        <label class="font-weight-bold" for="note_receipt_taxable_real_value">
            Patrimônio
        </label>
        <input value="{{ old('note_receipt_taxable_real_value', number_format($awardTaxableValue, 2, ',', '.')) }}" class="form-control text-uppercase sgi-border-2" data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," type="text" id="note_receipt_taxable_real_value" name="note_receipt_taxable_real_value" placeholder="Valor de patrimônio" />
    </div>

    <div class="form-group other_values py-1 {{ $otherValues != 0 ? '' : 'd-none' }}">
        <label class="font-weight-bold" for="note_receipt_other_value">
            Outros valores
        </label>
        <input id="note_receipt_other_value" value="{{ $noteReceipt->note_receipt_other_value ?? null }}" class="form-control text-uppercase sgi-border-2" data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," type="text" id="note_receipt_other_value" name="note_receipt_other_value" placeholder="Valor" />
    </div>

    <div class="form-group mt-3 py-1">
        <label class="font-weight-bold" for="note_receipt_account_id">
            Conta de recebimento
            <span class="sgi-forced">*</span>
        </label>
        <select class="form-control text-uppercase sgi-border-2 sgi-select2" name="note_receipt_account_id" id="note_receipt_account_id">
            <option value="{{ $note->bank_account ?? '' }}">{{ $note->bank_account ?? 'Selecionar conta' }}</option>

            @foreach ($banks as $bank)
                @php
                    $bankId = $bank->id ?? '';
                    $bankName = $bank->bank_name ?? '';
                    $bankAccount = $bank->bank_account ?? '';
                    $bankAgency = $bank->bank_agency ?? '';
                @endphp
                @php
                    $noteBankId = $noteReceipt->note_receipt_account_id ?? null;
                @endphp
                <option {{ $bank->id == $noteBankId ? 'selected' : '' }} value="{{ $bankId }}">Banco: {{ $bankName }} | Agência: {{ $bankAgency }} - Conta: {{ $bankAccount }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mt-4">
        <label class="font-weight-bold" for="note_receipt_date">
            Data de recebimento
            <span class="sgi-forced">*</span>
        </label>
        <input class="form-control text-uppercase sgi-border-2" value="{{ date('Y-m-d') }}" type="date" id="note_receipt_date" name="note_receipt_date" />
    </div>

    <div class="form-group">
        <label class="font-weight-bold" for="exampleFormControlTextarea1">Observações</label>
        <textarea name="note_receipt_note" class="form-control text-uppercase sgi-border-2" placeholder="Observações de recebimento" id="exampleFormControlTextarea1" rows="4">{{ $noteReceipt->note_receipt_note ?? null }}</textarea>
    </div>

    <div class="form-group mt-4">
        <input class="form-control sgi-border-2" value="{{ \Request::get('nota_id') ?? null }}" type="hidden" id="note_receipt_id" name="note_receipt_id" />
    </div>
</div>
