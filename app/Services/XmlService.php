<?php

namespace App\Services;

use App\Helpers\XmlHelper;
use Illuminate\Console\Command;

class XmlService
{
    public function importProductsByXml(Command $command, $file_name)
    {
        XmlHelper::importProductXml($command, $file_name);
    }

    public function exportOrdersByXml(Command $command, int $total)
    {
        XmlHelper::exportOrderXml($command, $total);
    }
}
