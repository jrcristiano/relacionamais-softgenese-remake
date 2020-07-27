@include('components.forms.errors.error')
@csrf
<input type="hidden" name="id" value="{{ $provider->id ?? null }}">
<div class="form-group">
    <label class="font-weight-bold" for="provider_name">
        Nome do fornecedor
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('provider_name', $provider->provider_name ?? null) }}" type="text" id="provider_name" name="provider_name" placeholder="Fornecedor" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="provider_address">
       Endereço
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('provider_address', $provider->provider_address ?? null) }}" type="text" id="provider_address" name="provider_address" placeholder="Endereço" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="provider_cnpj">
       CPF/CNPJ
        <span class="sgi-forced">*</span>
    </label>
    <input maxlength="14" class="form-control text-uppercase sgi-border-2" value="{{ old('provider_cnpj', $provider->provider_cnpj ?? null) }}" type="text" id="provider_cnpj" name="provider_cnpj" placeholder="Documento" />
</div>
<div class="form-group">
    <label class="font-weight-bold" for="exampleFormControlTextarea1">Observação</label>
    <textarea name="provider_note" class="form-control text-uppercase sgi-border-2" placeholder="Observações de fornecedor..." id="exampleFormControlTextarea1" rows="4">{{ old('provider_note', $provider->provider_note ?? null) }}</textarea>
</div>
