@extends('layouts.admin')
@section('title', 'Novo depósito em conta')
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                    'title' => 'Novo depósito em conta',
                    'buttonTitle' => 'Voltar a home',
                    'route' => 'admin.home',
                    'icon' => 'fas fa-home'
                ])

            @include('components.message')

                <form id="award_form" class="mt-3 px-2" action="{{ route('admin.register.awardeds.store', ['pedido_id' => \Request::get('pedido_id')]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @include('components.forms.form_award')
                        <div class="form-group">
                            <button class="btn btn-success font-weight-bold mt-1 save-button" type="submit">
                                <i class="fas fa-arrow-right"></i> Salvar
                            </button>
                        </div>
                </form>
        </div>
    </div>
</div>
@endsection
