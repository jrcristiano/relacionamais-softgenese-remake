@extends('layouts.admin')
@section('content')
@section('title', 'Transferências')
<div class="container-fluid">
        <div class="row shadow bg-white rounded">
            @include('components.leftbar')
                <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
                    @include('components.header_content', [
                        'title' => 'Transferências',
                        'buttonTitle' => 'Nova transferência',
                        'route' => 'admin.financial.transfers.create',
                        'icon' => 'fas fa-plus'
                    ])

                @include('components.message')

                <div class="col-lg-12 mt-4 d-flex flex-nowrap mb-2">
                    {!! $transfers->links() !!}
                    <input id="filter_table" class="col-lg-3 ml-auto form-control mr-sm-2" type="text" placeholder="Conta debitada, creditada e etc." />
                </div>

                <table id="client_table" class="table table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Conta debitada</th>
                            <th class="sgi-show-or-not" scope="col">Conta creditada</th>
                            <th scope="col">Valor</th>
                            <th class="sgi-show-or-not" scope="col">Tipo de transf.</th>
                            <th scope="col">Data</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transfers as $transfer)
                            <tr>
                                <td class="text-uppercase">{{ getBankById($transfer->transfer_account_debit) }}</td>
                                <td class="text-uppercase">{{ getBankById($transfer->transfer_account_credit) }}</td>
                                <td>R$ {{ $transfer->transfer_value_formatted }}</td>
                                <td class="text-uppercase">{{ $transfer->transfer_type == 1 ? 'PATRIMÔNIO' : 'PREMIAÇÃO' }}</td>
                                <td>{{ $transfer->transfer_date_formatted }}</td>
                                <td>
                                    <a data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-sm btn-primary" href="{{ route('admin.financial.transfers.edit', ['id' => $transfer->id]) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form class="d-inline sgi_form_delete" action="{{ route('admin.financial.transfers.destroy', [ 'id' => $transfer->id ]) }}" method="post">
                                        @csrf
                                        <button id="btn_delete" data-toggle="tooltip" data-placement="top" title="Remover" class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <td colspan="8" class="text-center"><i class="fas fa-frown"></i> Nenhuma transferência ainda registrada...</td>
                        @endforelse
                    </tbody>
                </table>
                    <div class="col-lg-12 d-flex justify-content-center">{!! $transfers->links() !!}</div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
