<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Backup the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $filename = 'backup-' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST'),
            env('DB_DATABASE'),
            $path
        );

        $returnVar = null;
        $output = null;

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error('Failed to backup the database.');
        } else {
            $this->info('Database backup was successful!');
        }
    }
}
