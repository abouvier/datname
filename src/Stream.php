<?php

declare(strict_types=1);

namespace DatName;

use DatName\Exception\InvalidArgument;
use DatName\Exception\Stream as StreamException;

final class Stream
{
    private bool $seekable;

    /**
     * @param resource $stream
     */
    public function __construct(private $stream)
    {
        if ('stream' != get_resource_type($stream)) {
            throw new InvalidArgument('not a stream');
        }
        $this->seekable = stream_get_meta_data($stream)['seekable'];
    }

    public function eof(): bool
    {
        return feof($this->stream);
    }

    /**
     * @return resource
     */
    public function getInnerStream()
    {
        return $this->stream;
    }

    public function read(int $length): string
    {
        $contents = fread($this->stream, $length);
        if (false === $contents) {
            throw new StreamException('read error');
        }

        return $contents;
    }

    public function seek(int $offset): void
    {
        if ($this->seekable) {
            if (0 != fseek($this->stream, $offset, SEEK_CUR)) {
                throw new StreamException('seek error');
            }
        } else {
            $this->read($offset);
        }
    }
}
