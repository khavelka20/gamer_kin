<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BulkDataImportService;

class BulkImportController extends Controller
{
    
    public function importGames()
    {
        $bulkImportService = new BulkDataImportService();
        
        return $bulkImportService->importGames();
    }
    
    public function importGamePlayClips(){
        $bulkImportService = new BulkDataImportService();
        $bulkImportService->importGameplayClips();
    }
}