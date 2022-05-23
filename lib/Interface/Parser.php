<?php

declare(strict_types=1);

namespace DatName\Interface;

interface Parser
{
    public function parse(string $input): iterable;

    public function parseFile(string $filename): iterable;

    public function validate(string $input): bool;

    public function validateFile(string $filename): bool;
}
