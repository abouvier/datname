<?php

declare(strict_types=1);

namespace DatName\Iterator;

/**
 * @extends \RecursiveFilterIterator<string, \SplFileInfo, \RecursiveIterator<string, \SplFileInfo>>
 */
final class RecursiveExtensionFilterIterator extends \RecursiveFilterIterator
{
    /**
     * @param \RecursiveIterator<string, \SplFileInfo> $iterator
     */
    public function __construct(\RecursiveIterator $iterator, private string $extension)
    {
        parent::__construct($iterator);
    }

    public function accept(): bool
    {
        return $this->hasChildren() or 0 == strcasecmp($this->current()->getExtension(), $this->extension);
    }
}
