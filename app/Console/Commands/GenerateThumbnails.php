<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Generalsetting;
use App\Helpers\Helper;
use App\Models\Product;

class GenerateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:thumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates thumbnails for FTP products based on their photos.';

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
     * @return mixed
     */
    public function handle()
    {
        if (Generalsetting::find(1)->ftp_folder) {
            $this->info('Gerando Thumbnails, aguarde...');
            $prods = Product::all();
            $prog = $this->output->createProgressBar(count($prods));
            foreach ($prods as $prod) {
                Helper::generateProductThumbnailsFtp(Generalsetting::find(1)->ftp_folder, $prod->ref_code);
                $prog->advance();
            }
            $prog->finish();
            $this->info('');
            $this->info('Thumbnails geradas com sucesso!');
        } else {
            $this->info('Não foi possível gerar as Thumbnails pois a integração com o FTP está desativada.');
        }
    }
}
