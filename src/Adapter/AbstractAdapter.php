<?php

declare(strict_types=1);

namespace AndKom\BerkeleyDb\Adapter;

use AndKom\BerkeleyDb\Exception;

/**
 * Class AbstractAdapter
 * @package AndKom\BerkeleyDb\Adapter
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