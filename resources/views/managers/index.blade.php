@extends('layouts.admin')
@section('title', 'Gerentes')
@section('content')
<div class="container-fluid">
        <div class="row shadow bg-white rounded">
            @include('components.leftbar')
            <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
                @include('components.header_content', [
                    'title' => 'Gerentes',
                    'buttonTitle' => 'Novo gerente',
                    'route' => 'admin.register.managers.create',
                    'icon' => 'fas fa-plus'
                ])

                @include('components.message')

                <div class="col-lg-12 mt-3 d-flex flex-nowrap mb-2">
                    {!! $managers->links() !!}
                    <input id="filter_table" class="col-lg-3 ml-auto form-control mr-sm-2" type="text" placeholder="Gerente, telefone e etc." />
                </div>

                <table id="client_table" class="table table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Gerente</th>
                            <th scope="col">Telefone</th>
                            <th class="sgi-show-or-not" scope="col">Email</th>
                            <th class="sgi-show-or-not" scope="col">CPF</th>
                            <th class="sgi-show-or-not" scope="col">Criado em</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($managers as $manager)
                            <tr>
                                <td class="text-uppercase">{{ $manager->manager_name }}</td>
                                <td class="manager_phone">{{ "{$manager->manager_tel_operator} {$manager->manager_phone}" }}</td>
                                <td class="text-uppercase sgi-show-or-not">{{ $manager->manager_email }}</td>
                                <td class="manager_cpf sgi-show-or-not">{{ $manager->manager_cpf }}</td>
                                <td class="sgi-show-or-not">{{ $manager->created_at_formatted }}</td>
                                <td>
                                    <a data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-sm btn-primary" href="{{ route('admin.register.managers.edit', ['id' => $manager->id]) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form class="d-inline sgi_form_delete" action="{{ route('admin.register.managers.destroy', [ 'id' => $manager->id ]) }}" method="post">
                                        @csrf
                                        <button data-toggle="tooltip" data-placement="top" title="Remover" class="btn btn-sm btn-danger btn_delete">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <td colspan="7" class="text-center"><i class="fas fa-frown"></i> Nenhum gerente ainda registrado...</td>
                        @endforelse
                    </tbody>
                </table>
                @if ($managers->count() >= 100)
                    <div class="col-lg-4 d-flex justify-content-between p-3" style="margin: 0 auto; border-top: 2px solid #eee;">
                        {!! $managers->links() !!}
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
    <script src="{{ asset('/js/managers/index-manager.js') }}"></script>
@endpush
