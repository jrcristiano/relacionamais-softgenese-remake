<?php

namespace App\Services;

use App\Helpers\Text;
use Bissolli\ValidadorCpfCnpj\Documento;
use yidas\phpSpreadsheet\Helper;

abstract class Service
{
    protected $result = [];
    protected $messageErrors = [];
    protected $awardedValue = 0;

    public function all()
    {
        return $this->service->all();
    }

    public function find($id)
    {
        return $this->service->find($id);
    }

    public function save($data, $id = null)
    {
        if (!$id) {
            return $this->service->save($data);
        }

        return $this->service->find($id)
            ->fill($data)
            ->save();
    }

    public function isDocumentValid($fileName, $posDocument, $posValue)
    {
        $excel = Helper::newSpreadsheet($fileName)->getRows();

        foreach ($excel as $key => $row) {
            if ($excel[$key][0] == null || $excel[$key][0] == '') {
                continue;
            }

            $key = $key + 1;

            $row[$posDocument] = Text::cleanDocument($row[$posDocument]);
            $row[$posDocument] = str_pad($row[$posDocument], 11, '0', STR_PAD_LEFT);

            $awardedValue = (float) $row[$posValue];

            $document = (string) Text::clean($row[$posDocument]);
            $documentValidator = new Documento($document);

            try {
                $this->isAllDocumentValid($documentValidator, $document, $key);
            } catch (\Throwable $e) {
                return redirect()->back()->withErrors('Erro ao enviar planilha, verifique-a e tente novamente.');
            }

            $this->awardedValue += $awardedValue;
        }

        if (in_array(false, $this->result)) {
            return false;
        }

        return true;
    }

    public function isAllDocumentValid(Documento $documentObj, $document, $key)
    {
        if (!$documentObj->isValid()) {
            $this->result[] = $documentObj->isValid();
            $this->messageErrors[] = [ 'line' => $key, 'document' => $document ];
            return false;
        }
        return true;
    }

    public function getMessageErrors()
    {
        return $this->messageErrors;
    }

    public function getAwardedValue()
    {
        return $this->awardedValue;
    }
}
