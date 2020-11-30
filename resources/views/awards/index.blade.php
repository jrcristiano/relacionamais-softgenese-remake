@extends('layouts.admin')
@section('title', 'Premiados')
@section('content')
<div class="container-fluid">
    <div class="row shadow bg-white rounded">
        @include('components.leftbar')
        <div class="col-lg-10 sgi-container shadow-sm rounded p-0">
            @include('components.header_content', [
                'title' => 'Premiados',
                'buttonTitle' => 'Nova premiação',
                'route' => 'admin.create',
                'icon' => 'fas fa-plus',
            ])

            @include('components.message')

            <div class="col-lg-12 mt-4 d-flex flex-nowrap mb-2">
                {!! $awards->links() !!}
                <input id="filter_table" class="col-lg-3 ml-auto form-control mr-sm-2" type="text" placeholder="Empresa, telefone e etc." />
            </div>

            <table id="client_table" class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Cliente premiado</th>
                        <th scope="col">Tipo de premiação</th>
                        <th scope="col">Status de premiação</th>
                        <th scope="col">Criado em</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($awards as $award)
                        <tr>
                            <td>{{ $award->awarded_value }}</td>
                            <td>{{ $award->awarded_type }}</td>
                            <td>{{ $award->awarded_status }}</td>
                            <td>{{ $award->created_at }}</td>
                            <td>
                                <a data-toggle="tooltip" data-placement="top" title="Visualizar" class="btn btn-sm btn-primary" href="{{ route('admin.register.awardeds.show', ['id' => $award->id]) }}">
                                    <i class="far fa-eye"></i>
                                </a>

                                <form class="d-inline sgi_form_delete" action="{{ route('admin.register.awardeds.destroy', [ 'id' => $award->id ]) }}" method="post">
                                    @csrf
                                    <button data-toggle="tooltip" data-placement="top" title="Remover" class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <td colspan="9" ><i class="fas fa-frown"></i> Nenhuma premiação ainda registrada...</td>
                    @endforelse
                </tbody>
            </table>

            @if ($awards->count() >= 100)
                <div class="col-lg-4 d-flex justify-content-between p-3" style="margin: 0 auto; border-top: 2px solid #eee;">
                    {!! $awards->links() !!}
                    <button id="sgi_btn_up" class="btn btn-lg btn-primary mr-3 mb-2"><i class="fas fa-arrow-up"></i></button>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('/js/jquery.mask.js') }}"></script>
<script src="{{ asset('/js/awards/index.js') }}"></script>
@endpush
