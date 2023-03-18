<?php

declare(strict_types=1);

namespace DatName\File;

use DatName\Exception\Filesystem\OpenFailedException;
use DatName\Game\Rom;
use DatName\Path;
use DatName\Stream;

class Zip extends Generic
{
    protected ?\ZipArchive $zip = null;

    public static function validate(Path $file): bool
    {
        if (!extension_loaded('zip')) {
            return false;
        }
        $zip = new \ZipArchive();
        if (true !== $zip->open($file->getPathname())) {
            return false;
        }

        return false !== $zip->locateName($file->getEntryname());
    }

    public function exists(string $newname): bool
    {
        return false !== $this->getInnerZip()->locateName($newname);
    }

    public function getDatname(Rom $rom): string
    {
        return $rom->getName();
    }

    public function getFastCrc(): string
    {
        return sprintf('%08x', $this->getInnerZip()->statName($this->getFilename())['crc']);
    }

    public function getFilename(): string
    {
        return $this->file->getEntryname();
    }

    protected function getInnerZip(): \ZipArchive
    {
        if (isset($this->zip)) {
            return $this->zip;
        }
        $zip = new \ZipArchive();
        if (true !== $zip->open($this->file->getPathname())) {
            throw new OpenFailedException($this->file);
        }

        return $this->zip = $zip;
    }

    public function getPathname(): string
    {
        return $this->file->getPathname().'#'.$this->getFilename();
    }

    public function getSize(): int
    {
        return $this->getInnerZip()->statName($this->getFilename())['size'];
    }

    public function getStream(): Stream
    {
        $stream = $this->getInnerZip()->getStream($this->getFilename());
        if (false === $stream) {
            throw new OpenFailedException($this->file);
        }

        return new Stream($stream);
    }

    public function rename(string $newname): bool
    {
        if (!$this->getInnerZip()->renameName($this->getFilename(), $newname)) {
            return false;
        }
        $this->file = $this->file->withEntryname($newname);
        unset($this->zip);

        return true;
    }
}
