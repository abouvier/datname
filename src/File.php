<?php

declare(strict_types=1);

namespace DatName;

use DatName\Exception\Filesystem;
use DatName\Exception\Filesystem\FileAlreadyExists;
use DatName\Game\Rom;
use DatName\Interface\File as FileInterface;

final class File
{
    private ?Hash $hash;

    public function __construct(
        private FileInterface $file,
        private array $algos,
        private bool $cache,
    ) {
    }

    public function __toString(): string
    {
        return strval($this->file);
    }

    public function getHash(): Hash
    {
        if ($this->cache and isset($this->hash)) {
            return $this->hash;
        }
        if ($this->algos == [Hash::CRC]) {
            return $this->hash = new Hash([
                Hash::CRC => $this->file->getCrc(),
            ]);
        }
        $hashes = [];
        foreach ($this->algos as $algo) {
            $hashes[$algo] = hash_init($algo);
        }
        $stream = $this->file->getStream();
        while (!$stream->eof()) {
            $data = $stream->read($this->file::READ_SIZE);
            foreach ($hashes as &$hash) {
                hash_update($hash, $data);
            }
        }

        return $this->hash = new Hash(array_map('hash_final', $hashes));
    }

    public function matches(Rom $rom): bool
    {
        return $this->file->getSize() == $rom->getSize() and $this->getHash()->equals($rom->getHash());
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
