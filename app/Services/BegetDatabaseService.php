<?php

namespace App\Services;

use App\Models\Database;
use Illuminate\Support\Facades\Log;

class BegetDatabaseService
{
    public function createBegetDatabase($data): bool
    {
        try {
            Database::create($data);
            return true;
        }catch (\Exception $error){
            Log::error('Failed to create subdomain: ' . $error->getMessage());
            return false;
        }
    }
}
