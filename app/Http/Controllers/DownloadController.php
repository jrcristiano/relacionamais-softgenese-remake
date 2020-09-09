<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function download(Request $request)
    {
        $filename = $request->get('fileName');
        $path = storage_path();
        $pathFileName = "{$path}/app/public/vincs/{$filename}";

        return response()->download($pathFileName);
    }
}
