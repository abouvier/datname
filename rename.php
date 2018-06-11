#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Logiqx\DataFile;
use Logiqx\File\Regular;
use Logiqx\File\Zip;

$xml = simplexml_load_file('dat/Nintendo - Nintendo Entertainment System (20180601-022820).dat');
$data = new DataFile($xml);
foreach (glob('rom/*') as $rom) {
    $info = new SplFileInfo($rom);
    try {
        $file = new Zip($info);
    } catch (Exception $e) {
        $file = new Regular($info);
    }
    if ($game = $data->search($file)) {
        $file->rename($game);
    }
}
