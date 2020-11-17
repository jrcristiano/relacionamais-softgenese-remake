@extends('layouts.admin')
@section('title', 'Editar recebimento')
@section('content')
<div class="container-fluid">
        <div class="row shadow bg-white rounded">
            @include('components.leftbar')
            <div class="col-lg-10 sgi-container shadow-sm rounded">
            @include('components.header_content', [
                'title' => 'Editar recebimento',
                'buttonTitle' => 'Voltar',
                'route' => url()->previous(),
                'icon' => 'fas fa-undo'
            ])

        <form class="mt-3" action="{{ route('admin.financial.note-receipts.update', ['id' => $id, 'nota_id' => \Request::get('nota_id'), 'pedido_id' => \Request::get('pedido_id')]) }}" method="post">
                @method('PUT')
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
