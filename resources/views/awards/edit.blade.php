@extends('layouts.admin')
@section('title', 'Editar premiação')
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Editar premiação',
                'buttonTitle' => 'Voltar',
                'route' => url()->previous(),
                'icon' => 'fas fa-undo'
            ])

            @include('components.message', ['message_name' => 'error'])

            @if ($award->awarded_type == 1)
                <form class="mt-3 px-2" action="{{ route('admin.register.acesso-cards.update', ['id' => $award->id, 'pedido_id' => \Request::get('pedido_id')]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('components.forms.form_acesso_card', ['id' => $award->id])

                    <div class="form-group">
                        <button id="btn-award-send" class="btn btn-success save-button" type="submit">
                            <i class="fas fa-arrow-right"></i> Salvar
                        </button>
                    </div>
                </form>
            @endif

            @if ($award->awarded_type == 2)
                <form class="mt-3 px-2" action="{{ route('admin.register.awardeds.update', ['id' => $award->id, 'pedido_id' => \Request::get('pedido_id')]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('components.forms.form_award', ['id' => $award->id])

                    <div class="form-group">
                        <button id="btn-award-send" class="btn btn-success save-button" type="submit">
                            <i class="fas fa-arrow-right"></i> Salvar
                        </button>
                    </div>
                </form>
            @endif

            @if ($award->awarded_type == 3)
                <form class="mt-3 px-2" action="{{ route('admin.register.payment-manuals.update', ['id' => $award->id, 'pedido_id' => \Request::get('pedido_id')]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('components.forms.form_payment_manual', ['id' => $award->id])

                    <div class="form-group">
                        <button id="btn-award-send" class="btn btn-success save-button" type="submit">
                            <i class="fas fa-arrow-right"></i> Salvar
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/select2-bootstrap4.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('/js/payment-manuals/create-edit-payment-manual.js') }}"></script>
@endpush
