<?php

namespace App\Facades\AwardTypes;

use App\BaseAcessoCardsCompleto;
use App\HistoryAcessoCard;
use App\Repositories\AwardRepository as AwardRepo;
use App\Repositories\BaseAcessoCardsCompletoRepository;
use App\Repositories\HistoryAcessoCardRepository;
use App\Services\AcessoCardService;
use App\Services\BaseAcessoCardsCompletoService;
use App\Services\HistoryAcessoCardService;

class AcessoCard extends Award
{
    protected $awardRepo;
    protected $service;

    public function __construct(AwardRepo $awardRepo, AcessoCardService $service)
    {
        $this->awardRepo = $awardRepo;
        $this->service = $service;
    }

    public function storeAward($file, array $data)
    {
        $fullFileName = $file->getFullFileName();
        $validDocument = $this->service->isDocumentValid($fullFileName, 0, 2);

        if ($file->validation() && $validDocument) {
            $awardedValue = $this->service->getAwardedValue();

            $data['awarded_value'] = $awardedValue;
            $data['awarded_upload_table'] = $fullFileName;

            $this->storeCard($data, $fullFileName);
        }
    }

    public function storeCard(array $data, $fullFileName)
    {
        $baseAcessoCardService = new BaseAcessoCardsCompletoService(new BaseAcessoCardsCompletoRepository(new BaseAcessoCardsCompleto()));
        $historyAcessoCard = new HistoryAcessoCardService(new HistoryAcessoCardRepository(new HistoryAcessoCard));

        $documents = $this->service->getData($fullFileName, 0);
        $names = $this->service->getData($fullFileName, 1);
        $values = $this->service->getData($fullFileName, 2);

        $demandId = $data['awarded_demand_id'];
        $data['awarded_value'] = array_sum($values);
        $save = $this->awardRepo->save($data);

        foreach ($documents as $key => $document) {
            $findBase = $baseAcessoCardService->findByDocument($document);
            $findAcesso = $this->service->findByDocument($document);

            $acessoCardNumber = $baseAcessoCardService->firstBaseAcessoCardNumberByDocument($document);
            $params = [];
            $acessoCardDocument = $findAcesso->acesso_card_document ?? null;
            $acessoCardName = $findAcesso->acesso_card_name ?? null;
            $params['acesso_card_name'] = $acessoCardDocument == $documents[$key] ? $acessoCardName : $names[$key];
            $params['acesso_card_value'] = $values[$key];
            $cardNumber = $acessoCardNumber->base_acesso_card_number ?? null;
            $params['acesso_card_number'] = $cardNumber;
            $params['acesso_card_document'] = $documents[$key];
            $params['acesso_card_spreadsheet_line'] = $key + 1;
            $params['acesso_card_demand_id'] = $demandId;
            $params['acesso_card_award_id'] = $save->id;

            $this->service->save($params);

            $unlikedCard = $baseAcessoCardService->firstUnlikedBaseCardCompleto();
            $unlikedCard = $unlikedCard->base_acesso_card_number;

            if (!$findBase && $data['awarded_status'] == 2) {
                $baseAcessoCardService->update([
                    'base_acesso_card_name' => $names[$key],
                    'base_acesso_card_cpf' => $document,
                ], 'base_acesso_card_number', $unlikedCard);
            }
        }
    }

    public function updateAward(array $data)
    {
        $this->updateCard($data);
    }

    public function updateCard(array $data)
    {
        $id = $data['id'];
        $fullFileName = $this->awardRepo->firstFileNameById($id);
        $fullFileName = $fullFileName->awarded_upload_table;

        $baseAcessoCardService = new BaseAcessoCardsCompletoService(new BaseAcessoCardsCompletoRepository(new BaseAcessoCardsCompleto()));
        $historyAcessoCard = new HistoryAcessoCardService(new HistoryAcessoCardRepository(new HistoryAcessoCard));

        $documents = $this->service->getData($fullFileName, 0);

        $names = $this->service->getData($fullFileName, 1);
        $values = $this->service->getData($fullFileName, 2);

        $data['awarded_value'] = array_sum($values);
        $this->awardRepo->save($data, $id);

        if ($data['awarded_status'] == 2) {
            foreach ($documents as $key => $document) {
                $findBase = $baseAcessoCardService->findByDocument($document);
                $baseAcessoCardNumber = $baseAcessoCardService->firstUnlikedBaseCardCompleto();
                $baseAcessoCardNumber = $baseAcessoCardNumber->base_acesso_card_number;

                if (!$findBase) {
                    $baseAcessoCardService->update([
                        'base_acesso_card_name' => $names[$key],
                        'base_acesso_card_cpf' => $documents[$key],
                    ], 'base_acesso_card_number', $baseAcessoCardNumber);

                    $params = [];
                    $params['acesso_card_number'] = $baseAcessoCardNumber;
                    $this->service->updateByDocument($params, $document);
                }

                $findAcessoCards = $this->service->getHistoriesByDocument($document);

                foreach ($findAcessoCards as $findAcessoCard) {

                    if (!$historyAcessoCard->findAcessoCardId($findAcessoCard->id)) {
                        $historyAcessoCard->save([
                            'history_acesso_card_id' => $findAcessoCard->id,
                            'history_base_id' => $baseAcessoCardService->findByDocument($document)->id,
                        ]);
                    }
                }
            }
        }
    }
}
