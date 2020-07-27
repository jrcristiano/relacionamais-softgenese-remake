@extends('layouts.admin')
@section('title', "Editar conta a receber")
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Conta a receber',
                'buttonTitle' => 'Voltar a home',
                'route' => 'admin.home',
                'icon' => 'fas fa-home'
            ])

            <div class="col-lg-12">
                <a href="{{ route('admin.financial.receives.edit', [ 'id' => $receive->id ]) }}" class="btn btn-sm btn-primary">
                    <i aria-hidden="true" class="fas fa-edit"></i> Editar
                </a>
                <form id="form_delete" class="d-inline" action="{{ route('admin.financial.receives.destroy', [ 'id' => $receive->id ]) }}" method="post">
                    @csrf
                    <button id="btn_delete" data-placement="top" class="btn btn-sm btn-danger">
                        <i class="fas fa-times"></i> Remover
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
