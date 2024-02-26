<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class SetupStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create specific required content folders';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $storage_path = "/storage/";

        $folders = [
            $storage_path . 'images',
            $storage_path . 'images/products',
            $storage_path . 'images/thumbnails',
            $storage_path . 'images/galleries',
            $storage_path . 'xml',
            '/assets/files',
        ];

        foreach ($folders as $folder) {
            try {
                if (!is_dir(public_path().$folder)) {
                    $this->info("creating folder {$folder}");
                    mkdir(public_path().$folder);
                }
            } catch (Exception $e) {
                $this->error("it was not possible to create folder {$folder}");
                $this->line($e->getMessage());
                return 1;
            }
        }

        $this->info('folders ready. Make sure to adjust permissions afterwards, if necessary');
        return 0;
    }
}
