<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use File;

class CheckDiscrepancies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:discrepancies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $products = \DB::select('SELECT id, photo, thumbnail FROM products WHERE photo IS NOT NULL AND photo != ""');
        $discrepancies = 0;
        if(count($products) > 0){
            $this->info('Verificando referências de Fotos, aguarde...');
            $prog = $this->output->createProgressBar(count($products));
            foreach($products as $prod){
                $photo = $prod->photo;
                if(!File::exists(public_path('storage/images/products/'.$photo))){
                    $prog->advance();
                    $discrepancies++;
                }
            }
            $prog->finish();
            $this->info('');
            $this->info("Existe(m) ".$discrepancies." discrepância(s) relacionada(s) a referências de fotos de Produtos.");
            if($discrepancies > 0){
                $this->info("O comando update:photoreferences pode ser utilizado agora.");
            }
        } else{
            $this->info("Existe(m) ".$discrepancies." discrepância(s) relacionada(s) a referências de fotos de Produtos.");
        }
    }
}
