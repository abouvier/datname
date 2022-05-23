<?php

declare(strict_types=1);

namespace DatName;

use DatName\Exception\Filesystem;
use DatName\Exception\Filesystem\FileAlreadyExists;
use DatName\Game\Rom;
use DatName\Hash\Algo;
use DatName\Interface\File as FileInterface;
use Stringable;

final class File implements Stringable
{
    private Hashes $hashes;

    public function __construct(
        private FileInterface $file,
        private Algos $algos,
        private bool $cache,
    ) {
    }

    public function __toString(): string
    {
        return strval($this->file);
    }

    public function getHashes(): Hashes
    {
        if ($this->cache and isset($this->hashes)) {
            return $this->hashes;
        }
        if (1 == count($this->algos) and $this->algos->contains(Algo::CRC)) {
            return $this->hashes = new Hashes([
                new Hash(Algo::CRC, $this->file->getCrc()),
            ]);
        }
        $hashes = [];
        foreach ($this->algos as $algo) {
            $hashes[$algo->value] = hash_init($algo->value);
        }
        $stream = $this->file->getStream();
        while (!$stream->eof()) {
            $data = $stream->read($this->file::READ_SIZE);
            foreach ($hashes as $hash) {
                hash_update($hash, $data);
            }
        }
        foreach ($hashes as $algo => &$hash) {
            $hash = new Hash(Algo::from($algo), hash_final($hash));
        }

        return $this->hashes = new Hashes($hashes);
    }

    public function matches(Rom $rom): bool
    {
        return $this->file->getSize() == $rom->getSize() and $this->getHashes()->matches($rom->getHashes());
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
