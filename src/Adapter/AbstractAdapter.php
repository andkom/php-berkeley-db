<?php

declare(strict_types=1);

namespace AndKom\PhpBerkeleyDb\Adapter;

use AndKom\PhpBerkeleyDb\Exception;

/**
 * Class AbstractAdapter
 * @package AndKom\PhpBerkeleyDb\Adapter
 */
abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * Read database keys.
     * @return \Generator
     * @throws Exception
     */
    public function read(): \Generator
    {
        if ($key = $this->firstKey()) {
            yield $key => $this->get($key);
        }

        while ($key = $this->nextKey()) {
            yield $key => $this->get($key);
        }
    }
}