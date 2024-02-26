<?php

namespace App\Console\Commands;

use App\Services\XmlService;
use Illuminate\Console\Command;

class OrderExport extends Command
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
    protected $signature = 'order:export {total=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Output last TOTAL orders to XML. Default is 10';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $total = $this->argument('total');
        $this->service->exportOrdersByXml($this, $total);

        return 0;
    }
}
