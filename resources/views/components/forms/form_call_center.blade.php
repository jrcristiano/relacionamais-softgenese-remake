@include('components.forms.errors.error')
@php
    // dd($callCenter);
@endphp
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
        $awardType = $callCenter->award_type ?? null;
    @endphp
    <div class="form-control text-uppercase sgi-border-2">
        {{ $awardType == 1 || \Request::get('tipo_cartao') == 'completo' ? 'completo' : 'compras' }}
    </div>
</div>

<input type="hidden" name="call_center_award_type" value="{{ request('tipo_cartao') == 'completo' ? 1 : 2 }}" />

<div class="form-group">
    <label class="font-weight-bold" for="call_center_subproduct">
        Subproduto
        <span class="sgi-forced">*</span>
    </label>
    @php
        $baseStatus = $callCenter->base_acesso_card_status ?? null;
        $awardType = $callCenter->award_type ?? null;
    @endphp
    <div class="form-control text-uppercase sgi-border-2">
        {{ $awardType == 1 || \Request::get('tipo_cartao') == 'completo' ? 'acessocard completo' : 'acessocard compras' }}
    </div>
</div>

<input type="hidden" name="call_center_subproduct" value="{{ request('tipo_cartao') == 'completo' ? 1 : 2 }}" />

<div class="form-group">
    <label class="font-weight-bold">
        Premiado
        <span class="sgi-forced">*</span>
    </label>
    <div class="form-control text-uppercase sgi-border-2">
        {{ request('premiado') }} | {{ request('document') }}
    </div>
</div>

<input type="hidden" name="call_center_acesso_card_id" value="{{ \Request::get('acesso_card_id') }}" />
<input type="hidden" name="call_center_acesso_card_shopping_id" value="{{ \Request::get('acesso_card_shopping_id') }}" />

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

@php
    $previousCard = $previousCard->base_acesso_card_proxy ?? null;
    // dd($previousCard);
@endphp

@if ($previousCard)
    <div class="row px-3 mb-3">
        <label class="font-weight-bold">
            Proxy cancelado
        </label>
        <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
            {{ $previousCard }}
        </div>
    </div>
@endif

@php
    $currencyCard = $currencyCard->base_acesso_card_proxy ?? null;
@endphp

@if ($currencyCard)
    <div class="row px-3 mb-3">
        <label class="font-weight-bold">
            Proxy ativo
        </label>
        <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
            {{ $currencyCard }}
        </div>
    </div>
@endif
