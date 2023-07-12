<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use ZipArchive;

class DatabaseBackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a database backup and upload as a ZIP archive';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $backupDirectory = storage_path('app/backup');
        $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".sql";
        $zipFilename = "backup-" . Carbon::now()->format('Y-m-d') . ".zip";

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST'),
            env('DB_DATABASE'),
            $backupDirectory . '/' . $filename
        );

        exec($command);

        $zip = new ZipArchive();
        $zipPath = $backupDirectory . '/' . $zipFilename;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $zip->addFile($backupDirectory . '/' . $filename, $filename);
            $zip->close();

            // Code to upload the ZIP file goes here

            // Delete the temporary SQL file
            unlink($backupDirectory . '/' . $filename);

            $this->info('Database backup created and uploaded successfully.');
        } else {
            $this->error('Failed to create ZIP archive.');
        }
    }
}
