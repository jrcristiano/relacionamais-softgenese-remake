@include('components.forms.errors.error')
@php
    $id = $id ?? null;
@endphp
<div class="form-group">
    <label class="font-weight-bold" for="call_center_award_type">
        Tipo de premiação
        <span class="sgi-forced">*</span>
    </label>
    @php
        $baseStatus = $callCenter->base_acesso_card_status ?? null;
    @endphp
    <select {{ $baseStatus == 1 ? '' : 'disabled' }} class="form-control text-uppercase sgi-border-2" value="{{ old('call_center_award_type', $callCenter->call_center_award_type ?? null) }}" type="text" id="call_center_award_type" name="call_center_award_type">
        <option value="">SELECIONAR PREMIAÇÃO</option>
        @php
            $awardType = $callCenter->call_center_award_type ?? null;
        @endphp
        <option {{ $awardType == 1 || \Request::get('tipo_cartao') == 'completo' ? 'selected' : '' }} value="1">ACESSOCARD</option>
    </select>
</div>

@if ($baseStatus != 1)
    <input type="hidden" name="call_center_award_type" value="1" /> <!-- enquanto só tem 1 opção, depois deve ser programado -->
@endif

<div class="form-group">
    <label class="font-weight-bold" for="call_center_subproduct">
        Subproduto
        <span class="sgi-forced">*</span>
    </label>
    <select {{ $baseStatus == 1 ? '' : 'disabled' }} class="form-control text-uppercase sgi-border-2" value="{{ old('call_center_subproduct', $callCenter->call_center_subproduct ?? null) }}" type="text" id="call_center_subproduct" name="call_center_subproduct">
        <option value="">SELECIONAR SUBPRODUTO</option>
        @php
            $subproduct = $callCenter->call_center_subproduct ?? null;
        @endphp
        <option {{ $subproduct == 1 || \Request::get('tipo_cartao') == 'completo' ? 'selected' : '' }} value="1">ACESSOCARD COMPLETO</option>
        <option {{ $subproduct == 2 ? 'selected' : '' }} value="2">ACESSOCARD COMPRAS</option>
    </select>
</div>

@if ($baseStatus != 1)
    <input type="hidden" name="call_center_subproduct" value="{{ \Request::get('tipo_cartao') == 'completo' ? 1 : '' }}" />
@endif

<div class="form-group">
    <label class="font-weight-bold" for="call_center_acesso_card_id">
        Premiado
        <span class="sgi-forced">*</span>
    </label>
    <select disabled="disabled" id="select-awarded" class="form-control text-uppercase sgi-border-2 sgi-select2" value="{{ old('call_center_acesso_card_id', $callCenter->call_center_acesso_card_id ?? null) }}" type="text" id="call_center_acesso_card_id" name="call_center_acesso_card_id">
        <option value="">SELECIONAR PREMIADO</option>
        @foreach ($acessoCards as $acessoCard)
        @php
            $document = $acessoCard->acesso_card_document ?? null;
        @endphp
            <option data-status="{{ $acessoCard->base_acesso_card_status }}" {{ $document == \Request::get('document') || $acessoCard->acesso_card_name == \Request::get('premiado') ? 'selected' : '' }} value="{{ $acessoCard->id }}">{{ $acessoCard->acesso_card_document }} | {{ $acessoCard->acesso_card_name_formatted }}</option>
        @endforeach
    </select>
</div>

<input type="hidden" name="call_center_acesso_card_id" value="{{ \Request::get('acesso_card_id') }}" />

<div class="form-group mt-4">
    <label class="font-weight-bold" for="call_center_phone">
        Telefone
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('call_center_phone', $callCenter->call_center_phone ?? null) }}" type="text" id="call_center_phone" name="call_center_phone" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="call_center_phone">
        Email
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('call_center_email', $callCenter->call_center_email ?? null) }}" type="text" id="call_center_email" name="call_center_email" placeholder="Email" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="call_center_reason">
        Motivo
        <span class="sgi-forced">*</span>
    </label>
    @php
        $reason = $callCenter->call_center_reason ?? null;
    @endphp
    <select {{ $acessoCard->base_acesso_card_status ?? null == 2 ? 'disabled' : '' }} class="form-control text-uppercase sgi-border-2" type="text" id="call_center_reason" name="call_center_reason">
        <option value="">SELECIONAR MOTIVO</option>
        <option {{ $reason == 1 ? 'selected' : '' }} value="1">ROUBO</option>
        <option {{ $reason == 2 ? 'selected' : '' }} value="2">FURTO</option>
        <option {{ $reason == 3 ? 'selected' : '' }} value="3">PERDA</option>
        <option {{ $reason == 4 ? 'selected' : '' }} value="4">EXTRAVIO</option>
        <option {{ $reason == 5 ? 'selected' : '' }} value="5">INFORMAÇÃO</option>
    </select>
</div>

<div class="form-group">
    <label class="font-weight-bold" for="call_center_status">
        Status
        <span class="sgi-forced">*</span>
    </label>
    @php
        //dd($callCenter->call_center_status)
    @endphp
    <select class="form-control text-uppercase sgi-border-2" value="{{ old('call_center_status', $callCenter->call_center_status ?? null) }}" type="text" id="call_center_status" name="call_center_status">
        <option value="">SELECIONAR STATUS</option>
        @php
            $status = $callCenter->call_center_status ?? null;
        @endphp
        <option {{ $status == 1 || !$id ? 'selected' : '' }} value="1">PENDENTE</option>
        @if ($id)
            <option {{ $status == 2 ? 'selected' : '' }} value="2">RESOLVIDO</option>
        @endif

    </select>
</div>

<div class="form-group">
    <label class="font-weight-bold" for="call_center_comments">
        Observações
    </label>
    <textarea name="call_center_comments" placeholder="Observações" id="exampleFormControlTextarea1" rows="4" class="form-control text-uppercase sgi-border-2">
        {{ old('call_center_comments', $callCenter->call_center_comments ?? null) }}
    </textarea>
</div>

<div class="row px-3 mb-3">
    <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
        {{ $baseStatus == null ? 'NOVO CARTÃO' : ($baseStatus == 2 ? 'CARTÃO CANCELADO' : '') }}
    </div>
</div>
