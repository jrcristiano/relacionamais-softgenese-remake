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

        $documents = $this->service->getData($fullFileName, 0);
        $names = $this->service->getData($fullFileName, 1);
        $values = $this->service->getData($fullFileName, 2);

        $demandId = $data['awarded_demand_id'];
        $data['awarded_value'] = array_sum($values);

        $save = $this->awardRepo->save($data);

        foreach ($documents as $key => $document) {
            if ($names[$key] != null) {
                $document = str_pad($documents[$key], 11, '0', STR_PAD_LEFT);

                $findBase = $baseAcessoCardService->findByDocument($document);
                $findAcesso = $this->service->findByDocument($document);

                $acessoCardNumber = $baseAcessoCardService->firstBaseAcessoCardNumberByDocument($document);
                $params = [];
                $acessoCardDocument = $findAcesso->acesso_card_document ?? null;
                $acessoCardName = $findAcesso->acesso_card_name ?? null;
                $params['acesso_card_name'] = $acessoCardName && $acessoCardDocument == $documents[$key] ? $acessoCardName : $names[$key];
                $params['acesso_card_value'] = $values[$key];
                $cardNumber = $acessoCardNumber->base_acesso_card_number ?? null;
                $params['acesso_card_number'] = $cardNumber;
                $params['acesso_card_document'] = str_pad($documents[$key], 11, '0', STR_PAD_LEFT);
                $params['acesso_card_spreadsheet_line'] = $key + 1;
                $params['acesso_card_demand_id'] = $demandId;
                $params['acesso_card_award_id'] = $save->id;

                $status = $data['awarded_status'];

                if (!$findAcesso && !$baseAcessoCardService->findWhereStatusByDocument(2, $document) && $status == 3) {
                    $this->service->save($params);
                }

                if ($findAcesso && !$baseAcessoCardService->findWhereStatusByDocument(2, $document) && $status == 3) {
                    $proxy = $baseAcessoCardService->getBaseAcessoCardProxyByDocument($document);
                    $proxy = $proxy->base_acesso_card_proxy ?? null;
                    $params['acesso_card_proxy'] = $proxy;
                    $this->service->save($params);
                }

                if ($findAcesso && !$baseAcessoCardService->findWhereStatusByDocument(2, $document) && $status == 2) {
                    $this->service->saveByParam([
                        'acesso_card_number' => $params['acesso_card_number'],
                    ], 'acesso_card_document', $document);
                }

                $unlikedCard = $baseAcessoCardService->firstUnlikedBaseCardCompleto();
                $unlikedCard = $unlikedCard->base_acesso_card_number;

                if (!$findBase && $status == 2) {
                    $baseAcessoCardService->update([
                        'base_acesso_card_name' => $names[$key],
                        'base_acesso_card_cpf' => str_pad($document, 11, '0', STR_PAD_LEFT),
                        'base_acesso_card_status' => 1,
                    ], 'base_acesso_card_number', $unlikedCard);
                }

                if ($findAcesso && $baseAcessoCardService->findWhereStatusByDocument(2, $document) && $status == 3) {
                    $params['acesso_card_number'] = null;
                    $this->service->save($params);
                }
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
                if ($names[$key]) {
                    $document = str_pad($documents[$key], 11, '0', STR_PAD_LEFT);

                    $findBase = $baseAcessoCardService->findByDocument($document);
                    $findAcesso = $this->service->findByDocument($document);

                    $baseAcessoCard = $baseAcessoCardService->firstUnlikedBaseCardCompleto();
                    $baseAcessoCardProxy = $baseAcessoCard->base_acesso_card_proxy;
                    $baseAcessoCardNumber = $baseAcessoCard->base_acesso_card_number;

                    $unlikedCard = $baseAcessoCardService->firstUnlikedBaseCardCompleto();
                    $unlikedCard = $unlikedCard->base_acesso_card_number;

                    $unlikedProxy = $baseAcessoCardService->getBaseAcessoCardProxy($unlikedCard);
                    $unlikedProxy = $unlikedProxy->base_acesso_card_proxy;

                    if (!$findBase) {
                        $baseAcessoCardService->update([
                            'base_acesso_card_name' => $names[$key],
                            'base_acesso_card_cpf' => $document,
                            'base_acesso_card_status' => 1,
                        ], 'base_acesso_card_proxy', $baseAcessoCardProxy);

                        $params = [];
                        $params['acesso_card_number'] = $baseAcessoCardNumber;
                        $params['acesso_card_proxy'] = $baseAcessoCardProxy;
                        $this->service->updateByDocument($params, $document);
                    }

                    if ($findAcesso && $baseAcessoCardService->findWhereStatusByDocument(2, $document)) {
                        $this->service->saveByParam([
                            'acesso_card_number' => $unlikedCard,
                            'acesso_card_proxy' => $unlikedProxy,
                        ], 'acesso_card_award_id', $id);
                    }

                    if ($findAcesso && $baseAcessoCardService->findWhereStatusByDocument(2, $document)) {
                        $baseAcessoCardService->update([
                            'base_acesso_card_name' => $names[$key],
                            'base_acesso_card_cpf' => $document,
                            'base_acesso_card_status' => 1,
                        ], 'base_acesso_card_proxy', $baseAcessoCardProxy);

                        $this->service->saveByParam([
                            'acesso_card_number' => $unlikedCard,
                            'acesso_card_proxy' => $unlikedProxy,
                        ], 'acesso_card_award_id', $id);
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
}
