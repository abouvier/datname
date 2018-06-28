<?php

declare(strict_types=1);

namespace Logiqx;

use SimpleXMLElement;

class DataFile
{
    protected $xml;

    public function __construct(SimpleXMLElement $xml)
    {
        $this->xml = $xml;
    }

    public function search(File $file): ?Rom
    {
        $size = $file->getSize();
        if ($roms = $this->xml->xpath("//rom[@size='$size']")) {
            $md5 = $file->md5();
            foreach ($roms as $rom) {
                if (0 == strcasecmp((string) $rom['md5'], $md5)) {
                    return new Rom($rom);
                }
            }
        }

        return null;
    }
}
