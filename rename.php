#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Logiqx\DataFile;
use Logiqx\File;
use Logiqx\File\Nes;
use Logiqx\File\Zip;
use Logiqx\File\Zip\Nes as ZipNes;

$doc = new DOMDocument();
$doc->loadXML('<datafile/>');
$dir = new RecursiveDirectoryIterator('dat');
$filter = new class($dir) extends RecursiveFilterIterator {
    public function accept()
    {
        $extension = $this->current()->getExtension();

        return $this->hasChildren() or 0 == strcasecmp($extension, 'dat');
    }
};
$dats = new RecursiveIteratorIterator($filter);
foreach ($dats as $dat) {
    $xml = simplexml_load_file($dat);
    foreach ($xml->game as $game) {
        $dom_sxe = dom_import_simplexml($game);
        $dom_sxe = $doc->importNode($dom_sxe, true);
        $doc->firstChild->appendChild($dom_sxe);
    }
}
$xml = simplexml_import_dom($doc);
$data = new DataFile($xml);
foreach (glob('rom/*') as $rom) {
    foreach ([
        ZipNes::class,
        Zip::class,
        Nes::class,
        File::class,
    ] as $type) {
        try {
            $file = new $type($rom);
            if ($game = $data->search($file)) {
				$file->rename($game);
				echo $file, ' -> ', $game->name, PHP_EOL;
                break;
            }
        } catch (Exception $e) {
            unset($e);
        }
    }
}
