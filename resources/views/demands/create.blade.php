@extends('layouts.admin')
@section('title', 'Novo pedido')
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">

            @include('components.header_content', [
                'title' => 'Novo pedido',
                'buttonTitle' => 'Voltar a home',
                'route' => 'admin.home',
                'icon' => 'fas fa-home'
            ])

            @include('components.message', ['message_name' => 'message'])

            <form id="demand_form" class="mt-2 px-2" action="{{ route('admin.store') }}" method="post">
                @csrf
                @include('components.forms.form_demand')
                <div class="form-group">
                    <button class="btn btn-success font-weight-bold save-button" type="submit">
                        <i class="fas fa-arrow-right"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
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
