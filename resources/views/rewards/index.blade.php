@extends('layouts.admin')
@section('title', 'Premiados')
@section('content')
<div class="container-fluid">
    <div class="row shadow bg-white rounded">

        @include('components.leftbar')

        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            @include('components.header_content', [
                'title' => 'Premiados',
                'buttonTitle' => 'Voltar a home',
                'route' => 'admin.home',
                'icon' => 'fas fa-home'
            ])

            @include('components.message')

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('/js/receives/index-receive.js') }}"></script>
@endpush
