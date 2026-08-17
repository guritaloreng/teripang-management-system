<?php

namespace App\Http\Controllers;

use App\Services\BackupService;

class BackupController extends Controller
{
    public function index()
    {
        return view('backups.index');
    }

    public function store(BackupService $backupService)
    {
        $backup = $backupService->createBackup();

        return redirect()
            ->route('backups.index')
            ->with('success', 'Backup completed successfully.')
            ->with('backup', $backup);
    }
}
