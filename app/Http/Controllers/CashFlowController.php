<?php

namespace App\Http\Controllers;

use App\Repositories\BankRepository;
use App\Repositories\CashFlowRepository;
use Illuminate\Http\Request;

// Eu poderia colocar métodos de crud em um classe abstrata, maaaasss

class CashFlowController extends Controller
{
    private $cashFlowRepo;
    private $bankRepo;

    public function __construct(CashFlowRepository $cashFlowRepo, BankRepository $bankRepo)
    {
        $this->cashFlowRepo = $cashFlowRepo;
        $this->bankRepo = $bankRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bankId = $request->cash_flow_bank ?? null;
        $filter = array($request->cash_flow_in,
            $request->cash_flow_until
        );

        $bill = $this->cashFlowRepo->getBillTotal($filter, $bankId);
        $creditNf = $this->cashFlowRepo->getReceivePatrimonyTotal($filter, $bankId);
        $creditOtherValue = $this->cashFlowRepo->getReceiveOtherValueTotal($filter, $bankId);
        $creditTransferValue = $this->cashFlowRepo->getCreditTransferValueTotal($filter, $bankId);
        $debitTransferValue = $this->cashFlowRepo->getDebitTransferValueTotal($filter, $bankId);

        $patrimonyTotal = -$bill->value_total
            + $creditNf->value
            + $creditOtherValue->value
            + $creditTransferValue->value
            - $debitTransferValue->value;

        $cashFlows = $this->cashFlowRepo->getCashFlowsByPaginate(200, $filter, $bankId);
        $banks = $this->bankRepo->getBanks();

        return view('cash-flows.index', compact(
            'cashFlows',
            'patrimonyTotal',
            'banks'
        ));
    }
}
