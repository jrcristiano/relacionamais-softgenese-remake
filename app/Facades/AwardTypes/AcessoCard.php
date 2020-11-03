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
                $formattedDocument = \App\Helpers\Text::cleanDocument($document);
                $formattedDocument = str_pad($formattedDocument, 11, '0', STR_PAD_LEFT);
                $formattedDocument = trim($formattedDocument);

                $findBase = $baseAcessoCardService->findByDocument($formattedDocument);
                $findAcesso = $this->service->findByDocument($formattedDocument);

                $acessoCardNumber = $baseAcessoCardService->firstBaseAcessoCardNumberByDocument($formattedDocument);
                $cardNumber = $acessoCardNumber->base_acesso_card_number ?? null;

                $proxy = $baseAcessoCardService->getBaseAcessoCardProxyByDocument($formattedDocument);
                $proxy = $proxy->base_acesso_card_proxy ?? null;

                $params = [];
                $acessoCardDocument = $findAcesso->acesso_card_document ?? null;
                $acessoCardName = $findAcesso->acesso_card_name ?? null;
                $params['acesso_card_name'] = $acessoCardName && $acessoCardDocument == $formattedDocument ? $acessoCardName : $names[$key];
                $params['acesso_card_value'] = $values[$key];
                $params['acesso_card_number'] = $cardNumber;
                $params['acesso_card_proxy'] = $proxy;
                $params['acesso_card_document'] = str_pad($formattedDocument, 11, '0', STR_PAD_LEFT);
                $params['acesso_card_spreadsheet_line'] = $key + 1;
                $params['acesso_card_demand_id'] = $demandId;
                $params['acesso_card_award_id'] = $save->id;

                $status = $data['awarded_status'];

                if ($baseAcessoCardService->findBaseAcessoCardActiveByDocument($formattedDocument)) {
                    $params['acesso_card_already_exists'] = 1;
                }

                $statusActive = $baseAcessoCardService->findWhereStatusByDocument(1, $formattedDocument);
                $statusCancelled = $baseAcessoCardService->findWhereStatusByDocument(2, $formattedDocument);

                if (!$findAcesso && !$statusCancelled && $status == 3) {
                    $this->service->save($params);
                }

                if ($findAcesso && !$statusCancelled && $status == 3) {
                    $params['acesso_card_proxy'] = $proxy;
                    $this->service->save($params);
                }

                if ($findAcesso && !$statusCancelled && $status == 2) {
                    $this->service->saveByParam([
                        'acesso_card_number' => $params['acesso_card_number'],
                    ], 'acesso_card_document', $formattedDocument);
                }

                $unlikedCard = $baseAcessoCardService->firstUnlikedBaseCardCompleto();
                $unlikedCard = $unlikedCard->base_acesso_card_number;

                if (!$findBase && $status == 2) {
                    $baseAcessoCardService->update([
                        'base_acesso_card_name' => $names[$key],
                        'base_acesso_card_cpf' => str_pad($formattedDocument, 11, '0', STR_PAD_LEFT),
                        'base_acesso_card_status' => 1,
                    ], 'base_acesso_card_number', $unlikedCard);
                }

                if ($findAcesso && $statusCancelled && !$statusActive && $status == 3) {
                    $params['acesso_card_number'] = null;
                    $this->service->save($params);
                }

                $activeCard = $baseAcessoCardService->findByDocumentWhereCardActive($formattedDocument);

                if ($findAcesso && $statusCancelled && $statusActive && $status == 3) {
                    $params['acesso_card_number'] = $activeCard->base_acesso_card_number;
                    $params['acesso_card_proxy'] = $activeCard->base_acesso_card_proxy;
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

        $data['awarded_value'] = $this->awardRepo->find($id)->awarded_value;

        if ($data['awarded_status'] == 4) {
            $this->service->saveByParam([
                'acesso_card_chargeback' => 1
            ], 'acesso_card_award_id', $id);
        }

        $this->awardRepo->save($data, $id);

        if ($data['awarded_status'] == 2) {
            foreach ($documents as $key => $document) {
                if ($names[$key]) {
                    $formattedDocument = \App\Helpers\Text::cleanDocument($document);
                    $formattedDocument = str_pad($formattedDocument, 11, '0', STR_PAD_LEFT);
                    $formattedDocument = trim($formattedDocument);

                    $findBase = $baseAcessoCardService->findByDocument($formattedDocument);
                    $findAcesso = $this->service->findByDocument($formattedDocument);

                    $baseAcessoCard = $baseAcessoCardService->firstUnlikedBaseCardCompleto();
                    $baseAcessoCardProxy = $baseAcessoCard->base_acesso_card_proxy;
                    $baseAcessoCardNumber = $baseAcessoCard->base_acesso_card_number;

                    $unlikedCard = $baseAcessoCardService->firstUnlikedBaseCardCompleto();
                    $unlikedCard = $unlikedCard->base_acesso_card_number;

                    $unlikedProxy = $baseAcessoCardService->getBaseAcessoCardProxy($unlikedCard);
                    $unlikedProxy = $unlikedProxy->base_acesso_card_proxy;

                    if (!$findBase) {
                        //dd('aqui 1');
                        $baseAcessoCardService->update([
                            'base_acesso_card_name' => $names[$key],
                            'base_acesso_card_cpf' => $formattedDocument,
                            'base_acesso_card_status' => 1,
                        ], 'base_acesso_card_proxy', $baseAcessoCardProxy);

                        $params = [];
                        $params['acesso_card_number'] = $baseAcessoCardNumber;
                        $params['acesso_card_proxy'] = $baseAcessoCardProxy;
                        $this->service->updateByDocument($params, $formattedDocument);
                    }

                    $statusActive = $baseAcessoCardService->findWhereStatusByDocument(1, $formattedDocument);
                    $statusCancelled = $baseAcessoCardService->findWhereStatusByDocument(2, $formattedDocument);

                    $acessoCards = $this->service->findByAwardId($id);
                    if ($findAcesso && !$statusCancelled && $statusActive) {
                        // dd('aqui 2');
                        foreach ($acessoCards as $acessoCard) {
                            if (!$acessoCard->acesso_card_proxy) {
                                // dd($acessoCard->acesso_card_proxy);
                                // dd($acessoCardWithoutCards);
                                $this->service->saveByParam([
                                    'acesso_card_number' => $unlikedCard,
                                    'acesso_card_proxy' => $unlikedProxy,
                                ], 'acesso_card_proxy', $unlikedProxy);
                            }
                        }
                    }

                    if ($findAcesso && $statusCancelled && !$statusActive) {
                        //dd('aqui 3');

                        $acessoCardWithoutCards = $this->service->findCardCancelledByAwardId($id);
                        // dd($acessoCardWithoutCards);

                        $quantity = $acessoCardWithoutCards->count();
                        $newsUnlikedCards = $baseAcessoCardService->getCollectionUnlikedBaseCardCompleto($quantity);
                        // dd($newsUnlikedCards);

                        foreach ($newsUnlikedCards as $key => $newsUnlikedCard) {
                            $baseAcessoCardService->updateByParamWhereStatusNull([
                                'base_acesso_card_name' => $acessoCardWithoutCards[$key]->acesso_card_name,
                                'base_acesso_card_cpf' => $acessoCardWithoutCards[$key]->acesso_card_document,
                                'base_acesso_card_status' => 1
                            ], 'base_acesso_card_proxy', $newsUnlikedCard->base_acesso_card_proxy);
                        }

                        $docs = [];
                        foreach ($acessoCards as $acessoCard) {
                            if (!$acessoCard->acesso_card_proxy) {
                                $docs[] = $acessoCard->acesso_card_document;
                            }
                        }

                        foreach ($docs as $doc) {
                            $this->service->saveByParamWhereProxyNull([
                                'acesso_card_number' => $baseAcessoCardService->findActiveCardByDocument($doc)->base_acesso_card_number ?? null,
                                'acesso_card_proxy' => $baseAcessoCardService->findActiveCardByDocument($doc)->base_acesso_card_proxy ?? null,
                            ], 'acesso_card_document', $doc);
                        }
                    }

                    if ($findAcesso && $statusCancelled && $statusActive) {
                        // dd($statusActive);
                        // dd($statusActive);
                        // dd('aqui 4');
                        $docs = [];
                        foreach ($acessoCards as $acessoCard) {
                            if (!$acessoCard->acesso_card_proxy) {
                                $docs[] = $acessoCard->acesso_card_document;
                            }
                        }

                        foreach ($docs as $doc) {
                            $this->service->saveByParam([
                                'acesso_card_number' => $baseAcessoCardService->findActiveCardByDocument($doc)->base_acesso_card_number ?? null,
                                'acesso_card_proxy' => $baseAcessoCardService->findActiveCardByDocument($doc)->base_acesso_card_proxy ?? null,
                            ], 'acesso_card_document', $doc);
                        }
                    }

                    $findAcessoCards = $this->service->getHistoriesByDocument($formattedDocument);

                    foreach ($findAcessoCards as $findAcessoCard) {
                        if (!$historyAcessoCard->findAcessoCardId($findAcessoCard->id)) {
                            $historyAcessoCard->save([
                                'history_acesso_card_id' => $findAcessoCard->id,
                                'history_base_id' => $baseAcessoCardService->findByDocument($formattedDocument)->id,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
