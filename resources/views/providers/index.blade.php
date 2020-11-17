@extends('layouts.admin')
@section('title', 'Fornecedores')
@section('content')
<div class="container-fluid">
        <div class="row shadow bg-white rounded">
            @include('components.leftbar')
            <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
                <header class="sgi-content-header d-flex align-items-center">
                    <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="sgi-content-title">Fornecedores</h2>

                    @php
                        $pedidoId = $id ?? null;
                    @endphp
                    <a class="btn btn-primary sgi-btn-bold ml-auto mt-2" href="{{ route('admin.register.providers.create') }}">
                        <i class="fas fa-plus"></i> Novo fornecedor
                    </a>
                </header>

                @include('components.message')

                <div class="col-lg-12 mt-3 d-flex flex-nowrap mb-2">
                    {!! $providers->links() !!}
                    <input id="filter_table" class="col-lg-3 ml-auto form-control mr-sm-2" type="text" placeholder="Fornecedor, endereço e etc." />
                </div>

                <table id="client_table" class="table table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Fornecedor</th>
                            <th scope="col">Endereço</th>
                            <th scope="col">Documento</th>
                            <th scope="col">Criado em</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($providers as $provider)
                            <tr>
                                <td class="text-uppercase">{{ $provider->provider_name_formatted }}</td>
                                <td class="text-uppercase">{{ $provider->provider_address_formatted }}</td>
                                <td data-id="{{ $provider->id }}" id="provider_cnpj" class="provider_cnpj_{{ $provider->id }}">{{ $provider->provider_cnpj }}</td>
                                <td>{{ $provider->created_at_formatted }}</td>
                                <td>
                                    <a data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-sm btn-primary" href="{{ route('admin.register.providers.edit', ['id' => $provider->id]) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form class="d-inline sgi_form_delete" action="{{ route('admin.register.providers.destroy', [ 'id' => $provider->id ]) }}" method="post">
                                        @csrf
                                        <button id="btn_delete" data-toggle="tooltip" data-placement="top" title="Remover" class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <td class="text-center" colspan="6"><i class="fas fa-frown"></i> Nenhum fornecedor ainda registrado...</td>
                        @endforelse
                    </tbody>
                </table>
                @if ($providers->count() >= 100)
                    <div class="col-lg-4 d-flex justify-content-between p-3" style="margin: 0 auto; border-top: 2px solid #eee;">
                        {!! $providers->links() !!}
                        <button id="sgi_btn_up" class="btn btn-lg btn-primary mr-3 mb-2"><i class="fas fa-arrow-up"></i></button>
                    </div>
                @endif
            </div>
        </div>
    </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('/js/providers/index-provider.js') }}"></script>
@endpush
