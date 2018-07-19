<?php

declare(strict_types=1);

namespace AndKom\PhpBerkeleyDb\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class BDB
 * @package AndKom\PhpBerkeleyDb\Facade
 */
class BDB extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'bdb';
    }
}