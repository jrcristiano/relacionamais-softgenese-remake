@extends('layouts.admin')
@section('title', 'Editar pedido')
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Editar pedido',
                'buttonTitle' => 'Voltar',
                'route' => url()->previous(),
                'icon' => 'fas fa-undo'
            ])

            <form id="demand_form" class="mt-3 px-2" action="{{ route('admin.update', ['id' => $demand->id ]) }}" method="post">
                @csrf
                @method('PUT')
                @include('components.forms.form_demand')

                <div class="form-group">
                    <button class="btn btn-success save-button" type="submit">
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
<script src="{{ asset('/js/demands/create-edit-demand.js') }}"></script>
@endpush
