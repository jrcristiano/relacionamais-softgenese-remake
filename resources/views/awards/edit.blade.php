@extends('layouts.admin')
@section('title', 'Editar premiação')
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Editar premiação',
                'buttonTitle' => 'Voltar a home',
                'route' => 'admin.home',
                'icon' => 'fas fa-home'
            ])

            @include('components.message', ['message_name' => 'error'])

            <form class="mt-3 px-2" action="{{ route('admin.register.awardeds.update', [ 'id' => $award->id, 'pedido_id' => \Request::get('pedido_id') ]) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @if ($award->awarded_type == 2)
                    @include('components.forms.form_award', ['id' => $award->id])
                @endif
                @if ($award->awarded_type == 3)
                    @include('components.forms.form_manual_deposit')
                @endif
                <div class="form-group">
                    <button id="btn-award-send" class="btn btn-success save-button" type="submit">
                        <i class="fas fa-arrow-right"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
