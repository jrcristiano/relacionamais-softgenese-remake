<?php

namespace App\Facades\AwardTypes;

use App\BaseAcessoCardsCompleto;
use App\Repositories\AwardRepository as AwardRepo;
use App\Repositories\BaseAcessoCardsCompletoRepository;
use App\Services\AcessoCardService;
use App\Services\BaseAcessoCardsCompletoService;

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
            $find = $baseAcessoCardService->findByDocument($document);

            if ($find && $data['awarded_status'] == 3) {
                $acessoCardNumber = $baseAcessoCardService->firstBaseAcessoCardNumberByDocument($document);
                $params = [];
                $params['acesso_card_number'] = $acessoCardNumber->base_acesso_card_number;

                $this->service->storeCard($fullFileName, $demandId, $save->id, $params);
            }

            $unlikedCard = $baseAcessoCardService->firstUnlikedBaseCardCompleto();
            $unlikedCard = $unlikedCard->base_acesso_card_number;

            if (!$find && $data['awarded_status'] == 3) {
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

        $documents = $this->service->getData($fullFileName, 0);
        $names = $this->service->getData($fullFileName, 1);
        $values = $this->service->getData($fullFileName, 2);

        $demandId = \Request::get('pedido_id');
        $data['awarded_value'] = array_sum($values);
        $this->awardRepo->save($data, $id);

        if ($data['awarded_status'] == 2) {
            foreach ($documents as $key => $document) {
                $findAcessoCard = $this->service->findByDocument($document);
                $findAcessoCard = $findAcessoCard->acesso_card_document ?? null;

                if ($findAcessoCard == null) {
                    $acessoCardNumber = $baseAcessoCardService->firstBaseAcessoCardNumberByDocument($document);

                    $params = [];
                    $params['acesso_card_name'] = $names[$key];
                    $params['acesso_card_document'] = $document;
                    $params['acesso_card_value'] = $values[$key];
                    $params['acesso_card_number'] = $acessoCardNumber->base_acesso_card_number;
                    $params['acesso_card_spreadsheet_line'] = $key;
                    $params['acesso_card_demand_id'] = $demandId;
                    $params['acesso_card_award_id'] = $id;

                    $this->service->save($params);
                }
            }
        }
    }
}
