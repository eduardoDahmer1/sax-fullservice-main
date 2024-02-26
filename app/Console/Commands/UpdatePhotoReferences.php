<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use File;

class UpdatePhotoReferences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:photoreferences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates all photo references for Products.';

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
        // Atualiza as referências de foto para cada produto com Foto no banco e não na pasta.
        $products = \DB::select('SELECT id, photo, thumbnail FROM products WHERE photo IS NOT NULL AND photo != ""');
        $countProds = 0;
        if(count($products) > 0){
            $prog = $this->output->createProgressBar(count($products));
            $this->info('Atualizando referências de Fotos, aguarde...');
            $start = microtime(true);
            foreach($products as $prod){
                $photo = $prod->photo;
                if(!File::exists(public_path('storage/images/products/'.$photo))){
                    \DB::statement('UPDATE products SET photo = null, thumbnail = null WHERE id = '.$prod->id.'');
                    $prog->advance();
                    $countProds++;
                }
            }
            $time = microtime(true) - $start;
            $this->info('');
            $this->info('Tempo de execução: '.$time.' seconds.');
            if($countProds > 0){
                $prog->finish();
                $this->info('');
                $this->info('Referência(s) de '.$countProds.' fotos(s) atualizada(s) com sucesso!');
            }
        } else{
            // Se não existir fotos com discrepância, mas ainda existirem Thumbnails no banco que não estejam na pasta...
            $this->error('Não foram encontradas fotos de produtos para atualizar.');
            $thumbs = \DB::select('SELECT id, photo, thumbnail FROM products WHERE thumbnail IS NOT NULL AND thumbnail != ""');
            $countThumbs = 0;
            $prog = $this->output->createProgressBar(count($thumbs));
            if(count($thumbs) > 0){
                $this->info('Atualizando referências de Miniaturas, aguarde...');
                foreach($thumbs as $thumb){
                    $photo = $thumb->photo;
                    $thumbnail = $thumb->thumbnail;
                    if(!File::exists(public_path('storage/images/thumbnails/'.$thumbnail))){
                        \DB::statement('UPDATE products SET photo = null, thumbnail = null WHERE id = '.$thumb->id.'');
                    }
                    $prog->advance();
                    $countThumbs++;
                }
                if($countThumbs > 0){
                    $prog->finish();
                    $this->info('');
                    $this->info('Referência(s) de '.$countThumbs.' miniatura(s) atualizada(s) com sucesso!');
                }
            } else $this->error('Não foram encontradas miniaturas de produtos para atualizar.');
        }
    }
}
