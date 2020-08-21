<?php

namespace App\Facades;

use App\Repositories\Repository;
use App\Services\Service;
use App\Uploads\Facades\Upload;
use Illuminate\Http\Request;

class UploadAward
{
    private $awardRepo;
    private $service;

    public function __construct(Repository $awardRepo, Service $service)
    {
        $this->awardRepo = $awardRepo;
        $this->service = $service;
    }

    public function storeAward(Request $request, array $data, string $obj)
    {
        $file = $this->upload($request);
        $fileName = $file->getFileName();

        $award = new $obj($this->awardRepo, $this->service);
        if (!is_a($award, $obj)) {
            throw new \Exception('Objeto passado não é do tipo Award');
        }

        $award->storeAward($file, $data);

        return $this->errors($fileName);
    }

    private function upload(Request $request)
    {
        return Upload::file($request, 'awarded_upload_table', ['xlsx', 'xls']);
    }

    private function errors($fileName)
    {
        $messages = $this->service->getMessageErrors();

        if ($messages) {
            if (file_exists($fileName)) {
                unlink($fileName);
            }

            return $messages;
        }

        return true;
    }
}
