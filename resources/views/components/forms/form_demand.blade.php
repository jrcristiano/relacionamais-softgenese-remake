@include('components.forms.errors.error')

<input type="hidden" name="id" value="{{ $demand->id ?? '' }}">

<div class="form-group py-1">
    <label class="font-weight-bold" for="demand_client_cnpj">
        Empresa <span class="sgi-forced">*</span>
    </label>

    <select name="demand_client_cnpj" class="form-control sgi-select2 sgi-border-2">
        <option value="">SELECIONAR EMPRESA</option>
        @foreach ($clients as $client)
            @php
            if (isset($id)) {
                $selected = $client->client_cnpj === $demand->demand_client_cnpj ? 'selected' : '';
            }
            $selected = $selected ?? null;

            @endphp
            <option class="text-uppercase" {{ $selected }} value="{{ $client->client_cnpj }}">{{ $client->client_company }}</option>
        @endforeach
    </select>
</div>

<input type="hidden" value="{{ $demand->demand_client_name ?? null }}" id="demand_client_name" name="demand_client_name" />

<div class="form-group mt-3 py-2">
    <label class="font-weight-bold" for="demand_prize_amount">
        Valor de premiação
        <span class="sgi-forced">*</span>
    </label>
    <input required="required" data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," class="form-control text-uppercase sgi-border-2" value="{{ old('demand_prize_amount', $demand->demand_prize_amount_formatted ?? '') }}" type="text" id="demand_prize_amount" name="demand_prize_amount" placeholder="Valor de premiação (R$)" />
</div>

@php
    $otherValue = $demand->demand_other_value ?? null;
@endphp

<div class="form-group mt-3 py-2">
    <div class="form-check">
        <input {{ $otherValue ? 'checked="checked"' : '' }} class="form-check-input" type="checkbox" value="" id="add_value">
        <label class="form-check-label font-weight-bold" for="add_value">
            Valor adicional
        </label>
        @php
            $demandTaxableAmount = $demand->demand_taxable_amount ?? null;
            $demandTaxableManual = $demand->demand_taxable_manual ?? null;
        @endphp
            <input class="form-check-input ml-4" type="checkbox" {{ $demandTaxableManual > 0.01 ? 'checked="checked"' : '' }} id="trib_manual">
            <label class="form-check-label font-weight-bold ml-5" for="trib_manual">
                Valor trib. manual
            </label>
    </div>
</div>

<div class="form-group py-1">
    <label class="font-weight-bold checkbox_taxable_amount" for="demand_taxable_amount">
        Valor tributável
    </label>
    <input required="required" data-affixes-stay="true" data-prefix="R$ " {{ $demandTaxableAmount > 0.01 || $demandTaxableAmount == null ? 'disabled="disabled"' : '' }}  data-thousands="." data-decimal="," class="form-control text-uppercase sgi-border-2" value="{{ $demandTaxableAmount > 0.01 || $demandTaxableAmount == null ? $demand->demand_taxable_amount_formatted ?? null : $demand->demand_taxable_manual_formatted ?? null }}" type="text" id="{{ $demandTaxableAmount > 0.01 || $demandTaxableAmount == null ? 'demand_taxable_amount' : 'demand_taxable_manual' }}" name="{{ $demandTaxableAmount > 0.01 || $demandTaxableAmount == null ? 'demand_taxable_amount' : 'demand_taxable_manual' }}" placeholder="Valor tributável (R$)" />
</div>

<div class="form-group {{ $otherValue ? '' : 'd-none' }} other_value mt-3 py-2">
    <label class="font-weight-bold" for="demand_other_value">
        Outros valores
    </label>
    <input class="text-uppercase form-control sgi-border-2" required="required" data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," class="form-control sgi-border-2" value="{{ old('demand_other_value', $demand->demand_other_value_formatted ?? '') }}" type="text" id="demand_other_value" name="demand_other_value" placeholder="Valor (R$)" />
</div>
