<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use File;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Storage::exists('backup')) {
            Storage::makeDirectory('backup');
        }

        $filename = "recruiterlabs_db_" . Carbon::now()->format('Y-m-d-h-i-s') . ".sql";

        $command = "mysqldump --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " > " . storage_path() . "/app/backup/" . $filename;

        $returnVar = null;
        $output  = null;

        exec($command, $output, $returnVar);

        // Move a file to S3
        $sourcePath = storage_path() . "/app/backup/" . $filename;
        $destinationPath = 'BackupFiles/'. $filename;

        Storage::disk('s3')->put($destinationPath, file_get_contents($sourcePath));



        // Log the execution with a timestamp
        $timestamp = now()->format('Y-m-d H:i:s');
        $logMessage = "Backup file created at: " . $timestamp;
        $logFilePath = storage_path('/app/backup/db_backup_log.txt');

        // Append the log message to the file
        file_put_contents($logFilePath, $logMessage . PHP_EOL, FILE_APPEND);

        File::delete($sourcePath);

         // Delete backups older than 15 days from S3
         $this->deleteOldBackupsFromS3();
    }

    protected function deleteOldBackupsFromS3()
    {
        // Specify the number of days for which you want to keep backups
        $daysToKeep = 15;

        // Calculate the date threshold
        $thresholdDate = now()->subDays($daysToKeep);

        // Get a list of all files in the S3 directory
        $files = Storage::disk('s3')->files('BackupFiles');

        // Iterate through each file and delete if older than the threshold
        foreach ($files as $file) {
            $fileDate = Carbon::createFromTimestamp(Storage::disk('s3')->lastModified($file));

            if ($fileDate->lessThan($thresholdDate)) {
                Storage::disk('s3')->delete($file);
            }
        }
    }
}
