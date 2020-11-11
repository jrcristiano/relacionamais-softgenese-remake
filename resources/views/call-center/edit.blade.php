@extends('layouts.admin')
@section('title', 'Novo chamado')
@section('content')
@php
    // dd($baseAcessoCardsCompletos);
@endphp
<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Novo chamado',
                'buttonTitle' => 'Voltar a home',
                'route' => 'admin.home',
                'icon' => 'fas fa-home'
            ])

            @include('components.message')

            <form class="mt-3 px-2" action="{{ route('admin.operational.call-center.update', ['id' => $callCenter->id]) }}" method="post">

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
