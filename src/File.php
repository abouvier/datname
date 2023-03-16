<?php

declare(strict_types=1);

namespace DatName;

use DatName\Exception\Filesystem;
use DatName\Exception\Filesystem\FileAlreadyExists;
use DatName\Game\Rom;
use DatName\Hash\Algo;
use DatName\Interface\File as FileInterface;
use Stringable;

class File implements Stringable
{
    public function __construct(
        private FileInterface $file,
        private Algos $algos,
    ) {
    }

    public function __toString(): string
    {
        return (string) $this->file;
    }

    public function getHashes(): Hashes
    {
        if ($this->algos->onlyCrc()) {
            return new Hashes([
                new Hash(Algo::CRC, $this->file->getFastCrc()),
            ]);
        }
        $contexts = [];
        foreach ($this->algos as $algo) {
            $contexts[$algo->value] = hash_init($algo->value);
        }
        $stream = $this->file->getStream();
        while (!$stream->eof()) {
            $data = $stream->read((int) $this->file::READ_SIZE);
            foreach ($contexts as $context) {
                hash_update($context, $data);
            }
        }
        $hashes = new Hashes();
        foreach ($contexts as $algo => $context) {
            $hashes[] = new Hash(Algo::from($algo), hash_final($context));
        }

        return $hashes;
    }

    public function matches(Rom $rom): bool
    {
        if ($this->file->getSize() != $rom->getSize()) {
            return false;
        }

        return $this->getHashes()->matches($rom->getHashes());
    }

    public function namedAfter(Rom $rom): bool
    {
        return $this->file->getFilename() == $rom;
    }

    public function rename(Rom $rom): void
    {
        $newname = $this->file->getDatname($rom);
        if ($this->file->exists($newname)) {
            throw new FileAlreadyExists(sprintf("cannot rename file '%s' to '%s' because the target already exists", $this->file, $newname));
        }
        if (!$this->file->rename($newname)) {
            throw new Filesystem(sprintf("cannot rename file '%s' to '%s'", $this->file, $newname));
        }
    }
}
