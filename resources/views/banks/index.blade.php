@extends('layouts.admin')
@section('content')
@section('title', 'Bancos')
<div class="container-fluid">
        <div class="row shadow bg-white rounded">
            @include('components.leftbar')
                <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
                    @include('components.header_content', [
                        'title' => 'Bancos',
                        'buttonTitle' => 'Novo banco',
                        'route' => 'admin.register.banks.create',
                        'icon' => 'fas fa-plus'
                    ])

                @include('components.message')

                <div class="col-lg-12 mt-4 d-flex flex-nowrap mb-2">
                    {!! $banks->links() !!}
                    <input id="filter_table" class="col-lg-3 ml-auto form-control mr-sm-2" type="text" placeholder="Banco, agência e etc." />
                </div>

                <table id="client_table" class="table table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Banco</th>
                            <th scope="col">Agência</th>
                            <th scope="col">Conta</th>
                            <th class="text-center sgi-show-or-not" scope="col">Criado em</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($banks as $bank)
                            <tr>
                                <td class="text-uppercase">{{ $bank->bank_name }}</td>
                                <td>{{ $bank->bank_agency }}</td>
                                <td>{{ $bank->bank_account }}</td>
                                <td class="sgi-show-or-not">{{ $bank->created_at_formatted }}</td>
                                <td>
                                    <a data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-sm btn-primary" href="{{ route('admin.register.banks.edit', ['id' => $bank->id]) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <td colspan="9"><i class="fas fa-frown"></i> Nenhum banco ainda registrado...</td>
                        @endforelse
                    </tbody>
                </table>
                    @if ($banks->count() >= 100)
                        <div class="col-lg-4 d-flex justify-content-between p-3" style="margin: 0 auto; border-top: 2px solid #eee;">
                            {!! $banks->links() !!}
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
