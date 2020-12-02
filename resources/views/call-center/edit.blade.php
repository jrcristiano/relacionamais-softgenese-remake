@extends('layouts.admin')
@section('title', 'Novo chamado')
@section('content')
@php
    // dd($callCenter);
@endphp
<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="sgi-content-title">Editar chamado</h2>

                @php
                    $pedidoId = $id ?? null;
                @endphp
                    <div class="ml-auto"></div>

                    @if ($awardedHasCards->count() > 0 && $callCenter->call_center_status == 1 && $callCenter->call_center_reason != 5)
                        <button type="button" class="btn btn-danger font-weight-bold mt-2 mr-1" data-toggle="modal" data-target="#exampleModal">
                            <i class="far fa-credit-card"></i> Gerar 2º via
                        </button>
                        <form class="mt-2 mr-1" action="{{ route('admin.operational.base-acesso-card-completo.update', ['proxy' => $callCenter->acesso_card_proxy ]) }}" method="post">

                            @csrf
                            @method('PUT')
                            <input type="hidden" value="2" name="cancel_call_center_status" />
                            <input type="hidden" value="{{ $id ?? null }}" name="cancel_call_center_id" />
                            <button class="btn btn-danger font-weight-bold" type="submit">
                                <i class="fas fa-power-off"></i> Cancelar cartão
                            </button>
                        </form>

                    @endif

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header border-bottom-0">
                              <h5 class="modal-title" id="exampleModalLabel">
                                  Um novo pedido será criado, por favor insira o valor de premiação.
                                </h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                <label class="font-weight-bold" for="prize_amount">
                                    Valor de Premiação
                                    <span class="sgi-forced">*</span>
                                </label>
                                <input type="text" required="required" data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," class="form-control" id="prize_amount">
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                              <form action="{{ route('admin.operational.base-acesso-card-duplicate.update', ['proxy' => $callCenter->acesso_card_proxy ]) }}" method="post">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="prize_amount_hidden" name="prize_amount" />
                                <input type="hidden" value="2" name="duplicate_call_center_status" />
                                <input type="hidden" value="{{ $id ?? null }}" name="duplicate_call_center_id" />
                                <button class="btn btn-primary font-weight-bold" type="submit">
                                    Ok
                                </button>
                            </form>
                            </div>
                          </div>
                        </div>
                      </div>

                <a class="btn btn-primary sgi-btn-bold mt-2" href="{{ route('admin.operational.acesso-cards-completo.show', ['document' => \Request::get('document')]) }}">
                    <i class="fas fa-eye"></i> Consultar premiado
                </a>
                <a class="btn btn-primary sgi-btn-bold ml-1 mt-2" href="{{ url()->previous() }}">
                    <i class="fas fa-undo"></i> Voltar
                </a>
            </header>

            @include('components.message')

            <form class="mt-3 px-2" action="{{ route('admin.operational.call-center.update', [
                'tipo_cartao' => 'completo',
                'premiado' => \Request::get('premiado'),
                'document' => \Request::get('document'),
                'id' => $id
            ]) }}" method="post">

                <div class="form-group">
                    @csrf
                    @method('PUT')
                    @include('components.forms.form_call_center')
                    <button class="btn btn-success font-weight-bold mt-1 save-button" type="submit">
                        <i class="fas fa-arrow-right"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/select2.min.css') }}" />
<link rel="stylesheet" href="{{ asset('/css/select2-bootstrap4.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('/js/call-center/create-edit-call-center.js') }}"></script>
@endpush
