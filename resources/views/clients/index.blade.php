@extends('layouts.admin')
@section('title', 'Clientes')
@section('content')
<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            @include('components.header_content', [
                'title' => 'Clientes',
                'buttonTitle' => 'Novo cliente',
                'route' => 'admin.register.clients.create',
                'icon' => 'fas fa-plus'
            ])

            @include('components.message')

            <div class="col-lg-12 mt-3 d-flex flex-nowrap mb-2">
                {!! $clients->links() !!}
                <input id="filter_table" class="col-lg-3 ml-auto form-control mr-sm-2" type="text" placeholder="Empresa, telefone e etc." />
            </div>

            <table id="client_table" class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Empresa</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Documento</th>
                        <th class="sgi-show-or-not" scope="col">Inscrição estadual</th>
                        <th class="sgi-show-or-not" scope="col">Criado em</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td class="text-uppercase client_company">{{ $client->client_company_formatted }}</td>
                            <td class="client_phone">{{ $client->client_phone }}</td>
                            <td class="client_cnpj">{{ $client->client_cnpj }}</td>
                            <td class="sgi-show-or-not text-uppercase">{{ $client->client_state_reg ?? 'Não informado' }}</td>
                            <td class="sgi-show-or-not">{{ $client->created_at_formatted }}</td>
                            <td>
                                <a data-toggle="tooltip" data-placement="top" title="Visualizar" class="btn btn-sm btn-primary" href="{{ route('admin.register.clients.show', ['id' => $client->id]) }}">
                                    <i class="far fa-eye"></i>
                                </a>
                                <form class="d-inline sgi_form_delete" action="{{ route('admin.register.clients.destroy', [ 'id' => $client->id ]) }}" method="post">
                                    @csrf
                                    <button data-toggle="tooltip" data-placement="top" title="Remover" class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <td class="text-center" colspan="7"><i class="fas fa-frown"></i> Nenhum cliente ainda registrado...</td>
                    @endforelse
                </tbody>
            </table>
            @if ($clients->count() >= 100)
                <div class="col-lg-4 d-flex justify-content-between p-3" style="margin: 0 auto; border-top: 2px solid #eee;">
                    {!! $clients->links() !!}
                    <button id="sgi_btn_up" class="btn btn-lg btn-primary mr-3 mb-2"><i class="fas fa-arrow-up"></i></button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('/js/clients/index-client.js') }}"></script>
@endpush
