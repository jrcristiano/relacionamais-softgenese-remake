@extends('layouts.admin')
@section('title', "Lista de premiações")
@section('content')
@php
    // dd($acessoCards);
@endphp
<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="sgi-content-title">Premiados</h2>
                @php
                    $pedidoId = $id ?? null;
                @endphp
                <a class="btn btn-primary sgi-btn-bold ml-auto mt-2" href="{{ url()->previous() }}">
                    <i class="fas fa-undo"></i> Voltar
                </a>
                @php
                    $acessoCardsParted = $acessoCards[0]->award_already_parted ?? 1;

                    $arrCards = [];
                    foreach ($acessoCards as $acessoCard) {
                        if ($acessoCard->base_acesso_card_status == 1) {
                            $arrCards[] = $acessoCard->acesso_card_already_exists;
                        } else {
                            $arrCards[] = null;
                        }
                    }
                @endphp
                @if ($acessoCardsParted == 0 && in_array(null, $arrCards) && $acessoCards[0]->awarded_status != 1)
                    <form method="POST" class="mt-2 ml-1" action="{{ route('admin.register.part-acesso-cards.store', ['pedido_id' => \Request::get('pedido_id'), 'premiacao_id' => $id]) }}">
                        @csrf
                        <input name="acesso_card_id" type="hidden" value="{{ $id }}" />
                        <button class="btn btn-danger font-weight-bold" type="submit">Separar novos cartões</button>
                    </form>
                @endif
            </header>

            @include('components.message')

            <div class="col-lg-12 mt-4 d-flex flex-nowrap mb-2">
                {!! $acessoCards->appends(['pedido_id' => \Request::get('pedido_id')])->links() !!}
                <input id="filter_table" class="col-lg-3 ml-auto form-control mr-sm-2" type="text" placeholder="Nome, documento e etc." />
            </div>

            <table id="client_table" class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Proxy</th>
                        <th scope="col">Cartão</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($acessoCards as $acessoCard)
                        <tr>
                            @php
                                $alreadyExists = $acessoCard->acesso_card_already_exists == null && $acessoCard->awarded_status != 3;
                            @endphp
                            <td class="text-uppercase {{ $alreadyExists ? 'font-weight-bold' : '' }}">{{ $acessoCard->acesso_card_name }}</td>
                            <td class="spreadsheet_document" >{{ $acessoCard->acesso_card_document }}</td>
                            <td class="{{ $alreadyExists ? 'font-weight-bold' : '' }}">{{ $acessoCard->acesso_card_proxy ? $acessoCard->acesso_card_proxy : $acessoCard->base_acesso_card_proxy }}</td>
                            <td class="{{ $alreadyExists ? 'font-weight-bold' : '' }}">{{ $acessoCard->acesso_card_number ? $acessoCard->acesso_card_number : 'EMITIR CARTÃO' }}</td>
                            <td>R$ {{ $acessoCard->acesso_card_value_formatted }}</td>
                            <td>
                                @php
                                    // dd($acessoCard->acesso_card_chargeback)
                                @endphp
                                @if (!$acessoCard->acesso_card_chargeback && $acessoCard->awarded_status == 1)
                                    <form class="d-inline" action="{{ route('admin.api.acesso-card-api.update', ['id' => $acessoCard->acesso_card_id]) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="acesso_card_chargeback" value="1" />
                                        <input type="hidden" name="award_id" value="{{ $acessoCard->acesso_card_award_id }}" />
                                        <button data-toggle="tooltip" data-placement="top" title="Estornar" class="btn btn-sm btn-danger sgi-cancel">
                                            <i class="fas fa-undo-alt"></i>
                                        </button>
                                    </form>
                                @endif
                                @if ($acessoCard->awarded_status == 3)
                                    <form class="d-inline sgi_form_delete" action="{{ route('admin.register.acesso-cards.destroy', ['id' => $acessoCard->acesso_card_id, 'card_id' => $id, 'pedido_id' => \Request::get('pedido_id') ]) }}" method="post">
                                        @csrf
                                        <button data-toggle="tooltip" data-placement="top" title="Remover" class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <td colspan="10" class="text-center">
                            <i class="fas fa-frown"></i> Nenhuma premiação ainda registrada...
                        </td>
                    @endforelse
                </tbody>
            </table>

            @if ($acessoCards->count() >= 200)
                <div class="col-lg-4 d-flex justify-content-between p-3" style="margin: 0 auto; border-top: 2px solid #eee;">
                    {!! $acessoCards->appends(['pedido_id' => \Request::get('pedido_id')])->links() !!}
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
