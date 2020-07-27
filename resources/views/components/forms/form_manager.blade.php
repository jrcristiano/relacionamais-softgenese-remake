@include('components.forms.errors.error')
@csrf
<input type="hidden" name="id" value="{{ $manager->id ?? null }}">
<div class="form-group">
    <label class="font-weight-bold" for="manager_name">
        Nome do gerente
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('manager_name', $manager->manager_name ?? null) }}" type="text" id="manager_name" name="manager_name" placeholder="Gerente" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="manager_phone">
        Telefone
        <span class="sgi-forced">*</span>
    </label>
    @php
        $operator = $manager->manager_tel_operator ?? null;
        $phone = $manager->manager_phone ?? null;
    @endphp
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('manager_name', $operator . $phone) }}" type="text" id="manager_phone" name="manager_phone" placeholder="Telefone" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="manager_email">
        Email
        <span class="sgi-forced">*</span>
    </label>
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('manager_email', $manager->manager_email ?? null) }}" type="text" id="manager_email" name="manager_email" placeholder="Email" />
</div>

<div class="form-group">
    <label class="font-weight-bold" for="manager_cpf">
        CPF <span class="sgi-forced">*</span>
    </label>
    <input class="form-control text-uppercase sgi-border-2" value="{{ old('manager_cpf', $manager->manager_cpf ?? null) }}" type="text" id="manager_cpf" name="manager_cpf" placeholder="CPF" />
</div>
