<div class="col-lg-2 sgi-vh-100 rounded-left">
    <button id="sgi-btn-close" class="btn btn-lg btn-primary">
        <i class="fas fa-close"></i>
    </button>
    <a href="{{ route('admin.home') }}" class="nav-brand">
        <div class="sgi-logo py-3 mt-2">
            <img src="{{ asset('/imgs/white-logo.png') }}" width="140" height="70" alt="Logo tipo da SoftGenese">
        </div>
    </a>
    <span class="sgi-tag mt-3">
        {{ \Auth::user()->name }}
    </span>
    <ul class="navbar-nav ml-auto mt-2">
        <ul class="nav flex-column sgi-left-bar">
            <li class="nav-item sgi-link-menu">
                <a class="btn btn-primary btn-block" href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Todos os pedidos</a>
            </li>
        </ul>
        <span class="sgi-tag mt-3">
            Registros
        </span>
        <ul class="nav flex-column sgi-left-bar">
            <li class="nav-item sgi-link-menu">
                <a class="btn btn-primary btn-block text-left" href="{{ route('admin.register.clients') }}">
                    <i class="fas fa-user-tie ml-4"></i> Clientes
                </a>
            </li>
            <li class="nav-item sgi-link-menu">
                <a class="btn btn-primary btn-block text-left" href="{{ route('admin.register.providers') }}">
                    <i class="fas fa-gift ml-4"></i> Fornecedores
                </a>
            </li>
            <li class="nav-item sgi-link-menu">
                <a class="btn btn-primary btn-block text-left" href="{{ route('admin.register.managers') }}">
                    <i class="fas fa-users-cog ml-4"></i> Gerentes
                </a>
            </li>
            <li class="nav-item sgi-link-menu">
                <a class="btn btn-primary btn-block text-left" href="{{ route('admin.register.banks') }}">
                    <i class="fas fa-university ml-4"></i> Bancos
                </a>
            </li>
        </ul>
        <span class="sgi-tag mt-3">Financeiro</span>
            <ul class="nav flex-column sgi-left-bar">
                <li class="nav-item sgi-link-menu">
                    <a class="btn btn-primary btn-block text-left" href="{{ route('admin.financial.bills', [
                        'bill_in' => date('Y-m-d'),
                        'bill_until' => date('Y-m-d')
                    ]) }}">
                        <i class="fas fa-file-invoice-dollar ml-4"></i> Contas a pagar
                    </a>
                </li>
                <li class="nav-item sgi-link-menu">
                    <a class="btn btn-primary btn-block text-left" href="{{ route('admin.financial.receives', ['receive_status' => 1]) }}">
                        <i class="fas fa-piggy-bank ml-4"></i> Contas a receber
                    </a>
                </li>
                <li class="nav-item sgi-link-menu">
                    <a class="btn btn-primary btn-block text-left" href="{{ route('admin.financial.shipments') }}">
                        <i class="fas fa-file-word ml-4"></i> Remessas
                    </a>
                </li>
                <li class="nav-item sgi-link-menu">
                    <a class="btn btn-primary btn-block text-left" href="{{ route('admin.financial.cash-flows', [
                        'cash_flow_in' => date('Y-m-d'),
                        'cash_flow_until' => date('Y-m-d')
                        ]) }}">
                        <i class="fas fa-box-open ml-4"></i> Fluxo de caixa
                    </a>
                </li>
                <li class="nav-item sgi-link-menu">
                    <a class="btn btn-primary btn-block text-left" href="{{ route('admin.financial.transfers') }}">
                        <i class="fas fa-exchange-alt ml-4"></i> Transferências
                    </a>
                </li>
            </ul>

            <span class="sgi-tag mt-3">
                Operacional
            </span>

            <ul class="nav flex-column sgi-left-bar">
                <li class="nav-item sgi-link-menu">
                    <a class="btn btn-primary btn-block text-left" href="{{ route('admin.operational.consult-acesso-cards') }}">
                        <i class="fas fa-credit-card ml-4"></i> Cartões AcessoCard
                    </a>
                </li>
                <li class="nav-item sgi-link-menu">
                    <a class="btn btn-primary btn-block text-left" href="{{ route('admin.operational.call-center') }}">
                        <i class="fas fa-headset ml-4"></i> Central de atendimento
                    </a>
                </li>
            </ul>

            <span class="sgi-tag mt-3">Mais</span>
                <ul class="nav flex-column sgi-left-bar">
                    <li class="nav-item sgi-link-menu">
                        <a class="btn btn-primary btn-block text-left" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt ml-4"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
    </div>
