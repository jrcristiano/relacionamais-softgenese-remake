@php
    $id = $id ?? null;
    $spreadsheet = $spreadsheetExists->awarded_upload_table ?? null;
@endphp

<input type="hidden" name="awarded_type" value="4" />

@if (!$id)
    <div class="form-group upload-file">
        <label class="font-weight-bold" for="awarded_upload_table">
            Arquivo
            <span class="sgi-forced">*</span>
        </label>
        <input class="btn cursor-pointer form-control sgi-border-dashed pt-3 pb-5" value="{{ old('awarded_upload_table', $award->awarded_upload_table ?? null) }}" type="file" id="awarded_upload_table" name="awarded_upload_table" />
    </div>
@endif

@php
    $awardedStatus = $award->awarded_status ?? null;
@endphp

<div class="form-group mt-3 status_group">
    <label class="font-weight-bold" for="awarded_status">
        Status
        <span class="sgi-forced">*</span>
    </label>
    <select name="awarded_status" id="awarded_status" class="form-control status_deposit_account" style="border: 2px solid #ced4da;">
        <option value="">SELECIONAR STATUS DE PREMIAÇÃO</option>
        @if ($id && $awardedStatus == 1)
            <option {{ $awardedStatus == 2 ? 'selected' : '' }} value="2">AGUARDANDO PAGAMENTO</option>
        @endif
        @if ($id && $awardedStatus == 2 && $award->awarded_value > 0)
            <option {{ $awardedStatus == 1 ? 'selected' : '' }} value="1">ENVIAR PARA REMESSA</option>
        @endif

        @if ($id && $awardedStatus == 2)
            <option {{ $awardedStatus == 1 ? 'selected' : '' }} value="4">CANCELAR PREMIAÇÃO</option>
        @endif

        @if ($id && $awardedStatus == 3)
            <option {{ $awardedStatus == 2 ? 'selected' : '' }} value="2">AGUARDANDO PAGAMENTO</option>
        @endif
        @if (!$id)
            <option selected value="3">PENDENTE</option>
        @endif
    </select>
</div>
