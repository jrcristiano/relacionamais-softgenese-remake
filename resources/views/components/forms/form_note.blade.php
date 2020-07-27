@include('components.forms.errors.error')
@csrf
@php
    $id = $id ?? null;
@endphp

<input type="hidden" name="id" value="{{ $id }}" />
<input type="hidden" name="note_demand_id" value="{{ \Request::get('pedido_id') ?? null }}" />

<div class="form-group py-1">
    <label class="font-weight-bold" for="note_number">
        Número de nota fiscal
    </label>
    <input class="form-control sgi-border-2" value="{{ old('note_number', $note->note_number ?? null) }}" type="text" id="note_number" name="note_number" placeholder="NÚMERO" />
</div>

<div class="form-group py-1">
    <label class="font-weight-bold" id="note_status">
        Status
        <span class="sgi-forced">*</span>
    </label>
    <select class="form-control note_status" style="border: 2px solid #ced4da;" name="note_status">
        @php
            $noteStatus = $note->note_status ?? null;
        @endphp
        <option selected {{ $noteStatus == 1 ? 'selected' : '' }} value="1">ABERTO</option>
        <option {{ $noteStatus == 2 ? 'selected' : '' }} value="2">RECEBIDO</option>
        <option {{ $noteStatus == 3 ? 'selected' : '' }} value="3">CANCELADO</option>
    </select>
</div>

<div class="form-group mt-4">
    <label class="font-weight-bold" for="note_created_at">
        Data de emissão
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control sgi-border-2" value="{{ $note->note_created_at ?? date('Y-m-d') }}" type="date" id="note_created_at" name="note_created_at" />
</div>


<input type="hidden" name="demand_client_cnpj" value="{{ $note->demand_client_cnpj ?? null }}">

<input type="hidden" name="demand_client_name" value="{{ $note->demand_client_name ?? null }}">

<input type="hidden" name="demand_prize_amount" value="{{ $note->prize_amount ?? null }}">

<input type="hidden" name="demand_taxable_amount" value="{{ $note->taxable_amount ?? null }}">

<input type="hidden" name="note_account_receipt_id" value="{{ $note->note_account_receipt_id ?? null }}">

<div class="form-group py-1">
    <label class="font-weight-bold" for="note_due_date">
        Data de vencimento
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control sgi-border-2" value="{{ old('note_due_date', $note->note_due_date ?? date('Y-m-d')) }}" type="date" id="note_due_date" name="note_due_date" />
</div>

@if ($id)
    <div class="form-group mt-4 d-none" id="sgi-note-receipt_date">
        <label class="font-weight-bold" for="note_receipt_date">
            Data de recebimento
            <span class="sgi-forced">*</span>
        </label>
        <input class="form-control sgi-border-2" value="{{ old('note_receipt_date', $note->note_receipt_date ?? date('Y-m-d')) }}" type="date" id="note_receipt_date" name="note_receipt_date" />
    </div>
@endif
