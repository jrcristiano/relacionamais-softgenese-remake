@include('components.forms.errors.error')
@csrf
<input type="hidden" name="id" value="{{ $client->id ?? null }}">
<div class="form-group">
    <label class="font-weight-bold" for="client_company">
        Nome da empresa
        <span class="sgi-forced">*</span>
    </label>
    <input required="required" class="form-control text-uppercase sgi-border-2" value="{{ old('client_company', $client->client_company ?? null) }}" type="text" id="client_company" name="client_company" placeholder="Empresa" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="client_cnpj">
        CNPJ <span class="sgi-forced">*</span>
    </label>
    <input required="required" class="form-control sgi-border-2" value="{{ old('client_cnpj', $client->client_cnpj ?? null) }}" type="text" id="client_cnpj" name="client_cnpj" placeholder="CNPJ" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="client_address">
        Endereço <span class="sgi-forced">*</span>
    </label>
    <input required="required" class="form-control text-uppercase sgi-border-2" value="{{ old('client_address', $client->client_address ?? null) }}" type="text" id="client_address" name="client_address" placeholder="Endereço" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="client_phone">
        Telefone
        <span class="sgi-forced">*</span>
    </label>
    <input required="required" class="form-control text-uppercase sgi-border-2" value="{{ old('client_phone', $client->client_phone ?? null) }}" type="text" id="client_phone" name="client_phone" placeholder="Telefone" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="client_responsable_name">
        Nome do responsável <span class="sgi-forced">*</span>
    </label>
    <input required="required" class="form-control text-uppercase sgi-border-2" value="{{ old('client_responsable_name', $client->client_responsable_name ?? null) }}" type="text" id="client_responsable_name" name="client_responsable_name" placeholder="Representante da empresa" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="client_email">
        Email
        <span class="sgi-forced">*</span>
    </label>
    <input required="required" class="form-control text-uppercase sgi-border-2" value="{{ old('client_email', $client->client_email ?? null) }}" type="email" id="client_email" name="client_email" placeholder="Email" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="client_manager">Gerente</label>
    <select class="form-control text-uppercase sgi-border-2" name="client_manager" id="client_manager">
        <option class="text-uppercase" selected value="{{ $client->manager->id ?? '' }}">SELECIONAR GERENTE</option>

        @foreach ($managers as $manager)
            <option class="text-uppercase" {{ $client->client_manager ?? null == $manager->id ?? null ? 'selected' : '' }} value="{{ $manager->id ?? null }}">{{ $manager->manager_name ?? null }}</option>
        @endforeach

    </select>
</div>

<div class="form-group">
    <label class="font-weight-bold" for="client_rate_admin">
        Taxa de administração <span class="sgi-forced">*</span>
    </label>
    <input required="required" class="form-control text-uppercase sgi-border-2" value="{{ old('client_rate_admin', $client->client_rate_admin_form ?? null) }}" type="text" id="client_rate_admin" name="client_rate_admin" placeholder="Taxa de administração (ex: 2,50%, 5%, 100%)" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="client_comission_manager">
        Comissão do gerente
        <span class="sgi-forced">*</span>
    </label>
    <input required="required" class="form-control text-uppercase sgi-border-2" value="{{ old('client_comission_manager', $client->client_comission_manager_form ?? null) }}" type="text" id="client_comission_manager" name="client_comission_manager" placeholder="Comissão do gerente (ex: 2,50%, 5%, 100%)" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="client_state_reg">
        Inscrição estadual
    </label>
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('client_state_reg', $client->client_state_reg ?? null) }}" type="text" id="client_state_reg" name="client_state_reg" placeholder="Inscrição estadual" />
</div>
