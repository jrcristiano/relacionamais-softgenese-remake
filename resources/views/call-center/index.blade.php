@extends('layouts.admin')
@section('title', 'Central de atendimento')
@section('content')
@php
    // dd($callCenters)
@endphp

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            <header class="sgi-content-header d-flex align-items-center">
                <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="sgi-content-title">Central de atendimento</h2>
                @php
                    $pedidoId = $id ?? null;
                @endphp
            </header>

            @include('components.message')

            <div class="col-lg-12 mt-4 d-flex flex-nowrap mb-2">
                <form method="get" class="col-md-12 d-flex p-0">
                    <input value="{{ \Request::get('search') ?? null }}" type="text" name="search" id="search" placeholder="NOME E DOCUMENTO..." class="form-control w-25">
                    <select class="form-control w-25 ml-2" name="status" id="status">
                        <option value="">SELECIONAR STATUS</option>
                        <option {{ \Request::get('status') == 1 ? 'selected' : '' }} value="1">PENDENTE</option>
                        <option {{ \Request::get('status') == 2 ? 'selected' : '' }} value="2">RESOLVIDO</option>
                    </select>
                    <button id="btn-date" type="submit" class="btn btn-primary mr-2 ml-2">
                        <i aria-hidden="true" class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <table id="client_table" class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Data</th>
                        <th scope="col">Nome do premiado</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Produto</th>
                        <th scope="col">Motivo</th>
                        <th scope="col">Status</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($callCenters as $callCenter)
                    <tr>
                        <td>{{ $callCenter->created_at_formatted }}</td>
                        <td class="text-uppercase">{{ $callCenter->acesso_card_name ?? $callCenter->acesso_card_shopping_name }}</td>
                        <td>{{ $callCenter->acesso_card_document ?? $callCenter->acesso_card_shopping_document }}</td>
                        <td>{{ $callCenter->call_center_subproduct == 1 ? 'ACESSOCARD COMPLETO' : 'ACESSOCARD COMPRAS' }}</td>
                        <td>
                            @php

                                if ($callCenter->call_center_reason == 1) {
                                    $reason = 'ROUBO';
                                }

                                if ($callCenter->call_center_reason == 2) {
                                    $reason = 'FURTO';
                                }

                                if ($callCenter->call_center_reason == 3) {
                                    $reason = 'PERDA';
                                }

                                if ($callCenter->call_center_reason == 4) {
                                    $reason = 'EXTRAVIO';
                                }

                                if ($callCenter->call_center_reason == 5) {
                                    $reason = 'INFORMAÇÃO';
                                }
                            @endphp
                            {{ $reason }}
                        </td>
                        <td>
                            {{ $callCenter->call_center_status == 1 ? 'PENDENTE' : 'RESOLVIDO' }}
                        </td>
                        <td>
                            @if ($callCenter->call_center_subproduct == 1)
                                <a data-toggle="tooltip"
                                    data-placement="top"
                                    title="Visualizar"
                                    class="btn btn-sm btn-primary"
                                    href="{{ route('admin.operational.call-center.show', [
                                        'cartao_id' => $callCenter->base_acesso_card_id,
                                        'tipo_cartao' => 'completo',
                                        'premiado' => $callCenter->acesso_card_name,
                                        'document' => $callCenter->acesso_card_document,
                                        'id' => $callCenter->id,
                                    ]) }}">
                                        <i class="far fa-eye"></i>
                                </a>
                                <a data-toggle="tooltip"
                                    data-placement="top"
                                    title="Editar"
                                    class="btn btn-sm btn-primary"
                                    href="{{ route('admin.operational.call-center.edit', [
                                        'cartao_id' => $callCenter->base_acesso_card_id,
                                        'tipo_cartao' => 'completo',
                                        'premiado' => $callCenter->acesso_card_name,
                                        'document' => $callCenter->acesso_card_document,
                                        'id' => $callCenter->id,
                                        'acesso_card_id' => $callCenter->acesso_card_id
                                    ]) }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @else
                            <a data-toggle="tooltip"
                                data-placement="top"
                                title="Visualizar"
                                class="btn btn-sm btn-primary"
                                href="{{ route('admin.operational.call-center.show', [
                                    'cartao_id' => $callCenter->base_acesso_card_id,
                                    'tipo_cartao' => 'compras',
                                    'premiado' => $callCenter->acesso_card_shopping_name,
                                    'document' => $callCenter->acesso_card_shopping_document,
                                    'id' => $callCenter->id,
                                ]) }}">
                                    <i class="far fa-eye"></i>
                            </a>
                            <a data-toggle="tooltip"
                                data-placement="top"
                                title="Editar"
                                class="btn btn-sm btn-primary"
                                href="{{ route('admin.operational.call-center.edit', [
                                    'cartao_id' => $callCenter->base_acesso_card_id,
                                    'tipo_cartao' => 'compras',
                                    'premiado' => $callCenter->acesso_card_shopping_name,
                                    'document' => $callCenter->acesso_card_shopping_document,
                                    'id' => $callCenter->id,
                                    'acesso_card_shopping_id' => $callCenter->acesso_card_shopping_id
                                ]) }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                        <td class="text-center" colspan="7">
                            <i class="fas fa-frown"></i> Nenhum chamado ainda registrado...
                        </td>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
