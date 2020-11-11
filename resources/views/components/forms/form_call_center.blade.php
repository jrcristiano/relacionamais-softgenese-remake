@php
    // dd($callCenter->call_center_base_acesso_card_completo_id)
@endphp
<div class="form-group">
    <label class="font-weight-bold" for="call_center_award_type">
        Tipo de premiação
        <span class="sgi-forced">*</span>
    </label>
    <select class="form-control text-uppercase sgi-border-2" value="{{ old('call_center_award_type', $callCenter->call_center_award_type ?? null) }}" type="text" id="call_center_award_type" name="call_center_award_type">
        <option value="">SELECIONAR PREMIAÇÃO</option>
        <option {{ $callCenter->call_center_award_type ?? null == 1 ? 'selected' : '' }} value="1">ACESSOCARD</option>
    </select>
</div>

<div class="form-group">
    <label class="font-weight-bold" for="call_center_subproduct">
        Subproduto
        <span class="sgi-forced">*</span>
    </label>
    <select class="form-control text-uppercase sgi-border-2" value="{{ old('call_center_subproduct', $callCenter->call_center_subproduct ?? null) }}" type="text" id="call_center_subproduct" name="call_center_subproduct">
        <option value="">SELECIONAR SUBPRODUTO</option>
        <option {{ $callCenter->call_center_subproduct ?? null == 1 ? 'selected' : '' }} value="1">ACESSOCARD COMPLETO</option>
        <option {{ $callCenter->call_center_subproduct ?? null == 2 ? 'selected' : '' }} value="2">ACESSOCARD COMPRAS</option>
    </select>
</div>

<div class="form-group">
    <label class="font-weight-bold" for="call_center_base_acesso_card_completo_id">
        Premiado
        <span class="sgi-forced">*</span>
    </label>
    <select class="form-control text-uppercase sgi-border-2 sgi-select2" value="{{ old('call_center_base_acesso_card_completo_id', $callCenter->call_center_base_acesso_card_completo_id ?? null) }}" type="text" id="call_center_base_acesso_card_completo_id" name="call_center_base_acesso_card_completo_id">
        <option value="">SELECIONAR PREMIADO</option>
        @foreach ($baseAcessoCardsCompletos as $baseAcessoCardsCompleto)
            <option {{ $callCenter->call_center_base_acesso_card_completo_id ?? null == $baseAcessoCardsCompleto->id ? 'selected' : '' }} value="{{ $baseAcessoCardsCompleto->id }}">{{ $baseAcessoCardsCompleto->base_acesso_card_cpf }} | {{ $baseAcessoCardsCompleto->base_acesso_card_proxy }} | {{ $baseAcessoCardsCompleto->base_acesso_card_name_formatted }}</option>
        @endforeach
    </select>
</div>

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
    <select class="form-control text-uppercase sgi-border-2" value="{{ old('call_center_reason', $callCenter->call_center_reason ?? null) }}" type="text" id="call_center_reason" name="call_center_reason">
        <option value="">SELECIONAR PREMIAÇÃO</option>
        <option {{ $callCenter->call_center_reason ?? null == 1 ? 'selected' : '' }} value="1">ROUBO</option>
        <option {{ $callCenter->call_center_reason ?? null == 2 ? 'selected' : '' }} value="2">FURTO</option>
        <option {{ $callCenter->call_center_reason ?? null == 3 ? 'selected' : '' }} value="3">PERDA</option>
        <option {{ $callCenter->call_center_reason ?? null == 4 ? 'selected' : '' }} value="4">EXTRAVIO</option>
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
        <option value="">SELECIONAR PREMIAÇÃO</option>
        @php
            $status = $callCenter->call_center_status ?? null;
        @endphp
        <option {{ $status == 1 ? 'selected' : '' }} value="1">PENDENTE</option>
        <option {{ $status == 2 ? 'selected' : '' }} value="2">RESOLVIDO</option>
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
