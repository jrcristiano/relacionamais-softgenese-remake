<?php
namespace App\Uploads;

use Illuminate\Http\Request;

class Upload extends AbstractUpload
{
    protected $file;
    protected $destiny;
    protected $fileNameDatabase;
    protected $fullFileName;

    public function file(Request $request, $field, $extensions, $destiny)
    {
        $this->file = $request->file($field);
        $this->extensions = $extensions;
        $this->destiny = $destiny;
        $this->fileName = "spreadsheets/{$this->name()}";
        $this->fullFileName = storage_path('app') . "/{$this->destiny}/spreadsheets/{$this->name()}";
        return $this;
    }

    public function validation()
    {
        $valid = $this->file->isValid() && in_array($this->extension(), $this->extensions);
        if ($valid) {
            return true;
        }

        return false;
    }

    public function send()
    {
        $upload = $this->file->storeAs('public/spreadsheets', $this->name());
        if (!$upload) {
            return false;
        }
        return $this;
    }
}
