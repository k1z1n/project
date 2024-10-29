<?php

namespace App\Services;

use App\Models\Subdomain;
use Illuminate\Support\Facades\Log;

class SubdomainService
{
    public function createSubdomain($data): bool
    {
        try {
            Subdomain::create($data);
            return true;
        }catch (\Exception $error){
            Log::error('Failed to create subdomain: ' . $error->getMessage());
            return false;
        }
    }
}
