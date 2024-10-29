<?php

namespace App\Services;

use App\Models\FileZilla;
use Illuminate\Support\Facades\Log;

class FileZillaService
{
    public function createFileZilla($data): bool
    {
        try {
            FileZilla::create($data);
            return true;
        }catch (\Exception $error){
            Log::error('Failed to create subdomain: ' . $error->getMessage());
            return false;
        }
    }
}
