@extends('layouts.admin')
@section('title', "Lista de premiações")
@section('content')
@php
    // dd($spreadsheets);
@endphp
<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0"><i class="fas fa-bars"></i></button>
                <h2 class="sgi-content-title">Premiados</h2>
                @php
                    $pedidoId = $id ?? null;
                @endphp
                <a class="btn btn-primary sgi-btn-bold ml-auto mt-2" href="{{ url()->previous() }}">
                    <i class="fas fa-undo"></i> Voltar
                </a>
            </header>

            @include('components.message')

            <div class="col-lg-12 mt-4 d-flex flex-nowrap mb-2">
                {!! $spreadsheets->appends(['pedido_id' => \Request::get('pedido_id')])->links() !!}
                <input id="filter_table" class="col-lg-3 ml-auto form-control mr-sm-2" type="text" placeholder="Nome, documento e etc." />
            </div>

            <table id="client_table" class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Banco</th>
                        <th scope="col">Agência</th>
                        <th scope="col">Conta</th>
                        <th scope="col">Tipo de conta</th>
                        <th scope="col">Criado em</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($spreadsheets as $spreadsheet)
                        <tr>
                            <td class="text-uppercase">{{ $spreadsheet->spreadsheet_name }}</td>
                            <td class="spreadsheet_document" >{{ $spreadsheet->spreadsheet_document }}</td>
                            <td>R$ {{ $spreadsheet->spreadsheet_value }}</td>
                            <td>{{ $spreadsheet->spreadsheet_bank }}</td>
                            <td>{{ $spreadsheet->spreadsheet_agency }}</td>
                            <td>{{ $spreadsheet->spreadsheet_account }}</td>
                            <td>{{ $spreadsheet->spreadsheet_account_type_formatted }}</td>
                            <td>{{ $spreadsheet->created_at_formatted }}</td>
                            <td>
                                @if ($spreadsheet->shipment_generated == 1 && $spreadsheet->awarded_status == 1 && !$spreadsheet->spreadsheet_chargeback)
                                    <form class="d-inline sgi_form_chargeback" action="{{ route('admin.api.spreadsheet-api.update', ['id' => $spreadsheet->id, 'premiado_id' => request('id')]) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="spreadsheet_chargeback" value="1" />
                                        <input type="hidden" name="award_id" value="{{ $spreadsheet->spreadsheet_award_id }}" />
                                        <button data-toggle="tooltip" data-placement="top" title="Estornar" class="btn btn-sm btn-danger sgi-cancel">
                                            <i class="fas fa-undo-alt"></i>
                                        </button>
                                    </form>
                                @endif

                                @if ($spreadsheet->spreadsheet_chargeback && !$spreadsheet->awarded_shipment_cancelled)
                                    <button data-toggle="tooltip" data-placement="top" title="Estornar" class="btn btn-sm btn-danger rounded-pill font-weight-bold disabled sgi-cancel">
                                        ESTORNADO
                                    </button>
                                @endif

                                @if ($spreadsheet->spreadsheet_chargeback && $spreadsheet->awarded_shipment_cancelled)
                                    <button data-toggle="tooltip" data-placement="top" title="Estornar" class="btn btn-sm btn-danger rounded-pill font-weight-bold disabled sgi-cancel">
                                        CANCELADO
                                    </button>
                                @endif
                                @if ($spreadsheet->awarded_status == 3)
                                    <a data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-sm btn-primary" href="{{ route('admin.register.spreadsheets.edit', ['id' => $spreadsheet->id, 'premiado_id' => $id, 'pedido_id' => \Request::get('pedido_id') ]) }}">
                                        <i aria-hidden="true" class="fas fa-edit"></i>
                                    </a>
                                @endif
                                @if ($spreadsheet->awarded_status == 3)
                                    <form class="d-inline sgi_form_delete" action="{{ route('admin.register.spreadsheets.delete', ['id' => $spreadsheet->id, 'premiado_id' => $id, 'pedido_id' => \Request::get('pedido_id') ]) }}" method="post">
                                        @csrf
                                        <button data-toggle="tooltip" data-placement="top" title="Remover" class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <td colspan="10" class="text-center"><i class="fas fa-frown"></i> Nenhuma premiação ainda registrada...</td>
                    @endforelse
                </tbody>
            </table>

            @if ($spreadsheets->count() >= 200)
                <div class="col-lg-4 d-flex justify-content-between p-3" style="margin: 0 auto; border-top: 2px solid #eee;">
                    {!! $spreadsheets->appends(['pedido_id' => \Request::get('pedido_id')])->links() !!}
                    <button id="sgi_btn_up" class="btn btn-lg btn-primary mr-3 mb-2"><i class="fas fa-arrow-up"></i></button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('/js/awards/show-spreadsheets.js') }}"></script>
@endpush
