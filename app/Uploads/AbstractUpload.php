<?php
namespace App\Uploads;

use Carbon\Carbon;

abstract class AbstractUpload
{
    protected function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function name()
    {
        $name = str_replace(' ', '_', strtolower($this->file->getClientOriginalName()));
        $name = Carbon::now() . $name;
        return preg_replace("/[^a-zA-Z._0-9]/", "",  $name);
    }

    protected function extension()
    {
        return strtolower($this->file->getClientOriginalExtension());
    }

    protected function path()
    {
        return strtolower($this->file->getRealPath());
    }

    protected function size(): string
    {
        return $this->file->getSize();
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getFullFileName()
    {
        return $this->fullFileName;
    }
}
