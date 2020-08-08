@extends('layouts.admin')
@section('title', 'Home')
@section('content')
@php
    // dd($demands);
@endphp
<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')

        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            @include('components.header_content', [
                'title' => 'Pedidos',
                'buttonTitle' => 'Novo pedido',
                'route' => 'admin.create',
                'icon' => 'fas fa-plus'
            ])

            @include('components.message', ['message_name' => 'message'])

            <div class="col-lg-12 mt-3 d-flex flex-nowrap mb-2 sgi-sub-content-header">
                @if (\Request::get('has_sale'))
                    <a href="{{ route('admin.home') }}">
                        <i class="far fa-eye"></i> Todos os pedidos
                    </a>
                @endif
                @if (!\Request::get('has_sale'))
                    <a href="{{ route('admin.home', ['has_sale' => '1']) }}">
                        <i class="far fa-eye"></i> Pedidos com saldo
                    </a>
                @endif
                <input id="filter_table" class="col-lg-3 ml-auto form-control mr-sm-2" type="text" placeholder="Empresa, valor de premiação e etc." />
            </div>

            <table id="client_table" class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Empresa</th>
                        <th scope="col">Valor de premiação</th>
                        <th class="sgi-show-or-not" scope="col">Saldo</th>
                        <th class="sgi-show-or-not" scope="col">Data de pedido</th>
                        <th class="sgi-show-or-not" scope="col">Nota fiscal</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($demands as $demand)
                        @if (\Request::get('has_sale') && \Request::get('has_sale') == 1)
                            @if ($demand->sale >= 0.01 || $demand->sale < -0.01)
                                <tr>
                                    <th>{{ $demand->id }}</th>
                                    <td class="text-uppercase client_name">
                                        <a style="color:#212529;" href="{{ route('admin.show', ['id' => $demand->id]) }}">{{ $demand->demand_client_name_formatted }}</a>
                                    </td>
                                    <td class="prize_amount">R$ {{ $demand->demand_prize_amount_formatted }}</td>

                                    <td class="sgi-show-or-not">R$ {{ number_format($demand->sale, 2, ',', '.') }}</td>
                                    <td class="sgi-show-or-not">{{ $demand->created_at_formatted }}</td>
                                    @php
                                        $route = route('admin.financial.notes.create', ['pedido_id' => $demand->id]);
                                        $icon = '<i class="far fa-sticky-note"></i>';
                                        $generateNF = "<a class='btn btn-primary btn-sm' href='$route'>{$icon} Gerar nota</a>";
                                    @endphp
                                    <td class="sgi-show-or-not">{!! $demand->note_number ?? $generateNF !!}</td>
                                    <td>
                                        <a data-toggle="tooltip" data-placement="top" title="Visualizar" class="btn btn-sm btn-primary" href="{{ route('admin.show', ['id' => $demand->id]) }}">
                                            <i class="far fa-eye"></i>
                                        </a>
                                        <a data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-sm btn-primary" href="{{ route('admin.edit', ['id' => $demand->id]) }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if (!$demand->note_number && !$demand->awarded_demand_id)
                                            <form class="d-inline sgi_form_delete" action="{{ route('admin.destroy', [ 'id' => $demand->id ]) }}" method="post">
                                                @csrf
                                                <button data-toggle="tooltip" data-placement="top" title="Remover" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <th>{{ $demand->id }}</th>
                                <td class="text-uppercase client_name">
                                    <a style="color:#212529;" href="{{ route('admin.show', ['id' => $demand->id]) }}">{{ $demand->demand_client_name_formatted }}</a>
                                </td>
                                <td class="prize_amount">R$ {{ $demand->demand_prize_amount_formatted }}</td>

                                <td class="sgi-show-or-not">R$ {{ number_format($demand->sale, 2, ',', '.') }}</td>
                                <td class="sgi-show-or-not">{{ $demand->created_at_formatted }}</td>
                                @php
                                    $route = route('admin.financial.notes.create', ['pedido_id' => $demand->id]);
                                    $icon = '<i class="far fa-sticky-note"></i>';
                                    $generateNF = "<a class='btn btn-primary btn-sm' href='$route'>{$icon} Gerar nota</a>";
                                @endphp
                                <td class="sgi-show-or-not">{!! $demand->note_number ?? $generateNF !!}</td>
                                <td>
                                    <a data-toggle="tooltip" data-placement="top" title="Visualizar" class="btn btn-sm btn-primary" href="{{ route('admin.show', ['id' => $demand->id]) }}">
                                        <i class="far fa-eye"></i>
                                    </a>
                                    <a data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-sm btn-primary" href="{{ route('admin.edit', ['id' => $demand->id]) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if (!$demand->note_number && !$demand->awarded_demand_id)
                                        <form class="d-inline sgi_form_delete" action="{{ route('admin.destroy', [ 'id' => $demand->id ]) }}" method="post">
                                            @csrf
                                            <button data-toggle="tooltip" data-placement="top" title="Remover" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endif

                        @empty
                            <td colspan="9" class="text-center"><i class="fas fa-frown"></i> Nenhum pedido ainda registrado...</td>
                        @endforelse
                </tbody>
            </table>
            @if ($demands->count() >= 500)
                <div class="col-lg-4 d-flex justify-content-between p-3" style="margin: 0 auto; border-top: 2px solid #eee;">
                    @if (\Request::get('has_sale') && \Request::get('has_sale') == 1)
                        {!! $demands->appends(['has_sale' => 1])->links() !!}
                        <button id="sgi_btn_up" class="btn btn-lg btn-primary mr-3 mb-2"><i class="fas fa-arrow-up"></i></button>
                    @else
                    {!! $demands->links() !!}
                    <button id="sgi_btn_up" class="btn btn-lg btn-primary mr-3 mb-2"><i class="fas fa-arrow-up"></i></button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('/js/demands/index-demand.js') }}"></script>
@endpush

