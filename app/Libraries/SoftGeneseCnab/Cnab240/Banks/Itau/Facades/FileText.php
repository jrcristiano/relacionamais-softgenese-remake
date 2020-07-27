<?php

namespace App\Libraries\SoftgeneseCnab\Cnab240\Banks\Itau\Facades;

class FileText
{
    public function make($content, $filename, $mode = 'w')
    {
        $content = (string) $content;
        $file = fopen($filename, $mode);
        fwrite($file, $content);
        fclose($file);
    }
}
