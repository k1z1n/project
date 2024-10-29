<?php

namespace App\Services;

use Illuminate\Http\Request;

class HelperService
{
    public function formatDate($dates)
    {
        return $dates->each(function ($date) {
            $date->formatted_created_at = $date->created_at->format('H:i d.m.y');
        });
    }

    public function uploadFile(Request $request, string $fieldName, string $directory): ?string
    {
        if ($request->hasFile($fieldName)) {
            return $request->file($fieldName)->store($directory, 'public');
        }
        return null;
    }

    public function returnBackWithError($error)
    {
        return redirect()->back()->with('error', $error)->withInput();
    }

    public function returnWithSuccess($route, $text, $id = null)
    {
        return redirect()->route($route, $id)->with('success', $text);
    }

    public function returnWithInfo($route, $text,  $id = null)
    {
        return redirect()->route($route, $id)->with('info', $text);
    }
}
