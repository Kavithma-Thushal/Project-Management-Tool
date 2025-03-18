<?php

namespace App\Classes;

use App\Models\Workspace;
use Illuminate\Support\Str;

class CodeGenerator
{
    public static function generateWorkspaceUuid(): string
    {
        return (string) Str::uuid();
    }

    public static function generateUniqueWorkspaceCode(): string
    {
        do {
            // Generate a random 8-character string
            $workspaceCode = strtoupper(Str::random(8));
        } while (Workspace::where('code', $workspaceCode)->exists());

        return $workspaceCode;
    }
}
