@extends('layouts.admin')
@section('title', 'Nova nota fiscal')
@section('content')

<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Nova nota fiscal',
                'buttonTitle' => 'Voltar',
                'route' => url()->previous(),
                'icon' => 'fas fa-undo'
            ])
            <form class="mt-3 px-2" action="{{ route('admin.financial.notes.store') }}" method="post">
                @include('components.forms.form_note')
                <div class="form-group">
                    <button class="btn btn-success font-weight-bold mt-1" type="submit">
                        <i class="fas fa-arrow-right"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
