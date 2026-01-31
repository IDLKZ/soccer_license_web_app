<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DatabaseBackupController extends Controller
{
    public function download($fileName)
    {
        // Authorization
        if (!Gate::allows('manage-db')) {
            abort(403, 'Unauthorized action.');
        }

        $filePath = storage_path('app/backups/' . $fileName);

        if (!file_exists($filePath)) {
            abort(404, 'Файл не найден');
        }

        return response()->download($filePath);
    }
}
