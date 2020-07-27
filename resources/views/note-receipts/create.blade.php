@extends('layouts.admin')
@section('title', 'Novo recebimento')
@section('content')
<div class="container-fluid">
        <div class="row shadow bg-white rounded">
            @include('components.leftbar')
            <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Novo recebimento',
                'buttonTitle' => 'Voltar a home',
                'route' => 'admin.home',
                'icon' => 'fas fa-home'
            ])

        <form class="mt-3 px-3" action="{{ route('admin.financial.note-receipts.store', ['nota_id' => $noteId, 'pedido_id' => \Request::get('pedido_id')]) }}" method="post">
                @include('components.forms.form_note_receipt')
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

@push('scripts')
    <script src="{{ asset('/js/notes/create-edit-notes.js') }}"></script>
@endpush
