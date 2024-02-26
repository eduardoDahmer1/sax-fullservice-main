<?php

namespace App\Console\Commands;

use App\Services\XmlService;
use Illuminate\Console\Command;

class ProductImport extends Command
{
    public $service;
    public $output;

    public function __construct()
    {
        $this->service = new XmlService;
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:import {file=Produtos.xml}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read XML file and import products';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file_name = $this->argument('file');
        $this->service->importProductsByXml($this, $file_name);

        return 0;
    }
}
