<?php

declare(strict_types=1);

namespace Logiqx;

use SimpleXMLElement;

class Rom
{
    protected $xml;

    public function __construct(SimpleXMLElement $xml)
    {
        $this->xml = $xml;
    }

    public function __get(string $name): string
    {
        return (string) $this->xml[$name];
    }
}
