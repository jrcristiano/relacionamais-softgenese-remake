@extends('layouts.admin')
@section('title', "{$client->client_company}")
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-3 rounded-0 "><i class="fas fa-bars"></i></button>
                <h1 class="font-weight-bold text-uppercase sgi-content-title">Cliente {{ $client->client_company }}</h1>
                <a class="btn btn-primary ml-auto mt-2 mr-1" href="{{ url()->previous() }}">
                    <i class="fas fa-undo"></i> Voltar
                </a>
                <a href="{{ route('admin.register.clients.edit', [ 'id' => $client->id ]) }}" class="btn btn-primary mt-2 mr-1">
                    <i aria-hidden="true" class="fas fa-edit"></i> Editar
                </a>
                <form id="form_delete" class="d-inline" action="{{ route('admin.register.clients.destroy', [ 'id' => $client->id ]) }}" method="post">
                    @csrf
                    <button id="btn_delete" data-placement="top" class="btn btn-danger mt-2">
                        <i class="fas fa-times"></i> Remover
                    </button>
                </form>
            </header>
            <div class="container-fluid mt-2">
                <div class="row p-3">
                    <label class="font-weight-bold">Endereço</label>
                    <div class="col-md-12 text-uppercase sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $client->client_address }}
                    </div>
                </div>
                <div class="row p-3">
                    <label class="font-weight-bold">Telefone</label>
                    <div id="client_phone" class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $client->client_phone }}
                    </div>
                </div>
                <div class="row p-3">
                    <label class="font-weight-bold">Responsável</label>
                    <div class="col-md-12 text-uppercase sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $client->client_responsable_name }}
                    </div>
                </div>
                <div class="row p-3">
                    <label class="font-weight-bold">CNPJ</label>
                    <div id="client_cnpj" class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $client->client_cnpj }}
                    </div>
                </div>

                @if ($client->manager_name)
                    <div class="row p-3">
                        <label class="font-weight-bold">Gerente</label>
                        <div class="col-md-12 text-uppercase sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                            {{ $client->manager_name }}
                        </div>
                    </div>
                @endif

                <div class="row p-3">
                    <label class="font-weight-bold">Taxa de administração</label>
                    <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $client->client_rate_admin_formatted }}
                    </div>
                </div>

                <div class="row p-3">
                    <label class="font-weight-bold">Comissão de gerente</label>
                    <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $client->client_comission_manager_formatted }}
                    </div>
                </div>

                @if ($client->client_state_reg)
                    <div class="row p-3">
                        <label class="font-weight-bold">Inscrição estadual</label>
                        <div class="col-md-12 text-uppercase sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                            {{ $client->client_state_reg }}
                        </div>
                    </div>
                @endif

                <div class="row p-3">
                    <label class="font-weight-bold">Criado em</label>
                    <div class="col-md-12 sgi-border-2 py-2 px-3 mt-1" style="border-radius: 0.25rem;">
                        {{ $client->created_at_formatted }}
                    </div>
                </div>

            </div>
        </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('/js/clients/create-edit-client.js') }}"></script>
@endpush
