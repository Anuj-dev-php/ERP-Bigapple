<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ZipArchive;
use File;
class ZipController extends Controller
{
    public function download()
    { 

        // File::makeDirectory(storage_path('app/public/download_reports/made_by_folder'));
 
        $zip = new ZipArchive;
   
        $fileName = 'myNewFile.zip';
   
        if ($zip->open(storage_path('app/public/download_reports/'.$fileName), ZipArchive::CREATE) === TRUE)
        {
            $files = File::files(storage_path('app/public/download_reports/test_folder') );
   
            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }
             
            $zip->close();
        }
    
        return response()->download(storage_path('app/public/download_reports/'.$fileName));

    }
    
}
