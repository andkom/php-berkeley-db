<?php

declare(strict_types=1);

namespace AndKom\BerkeleyDb\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class BDB
 * @package AndKom\BerkeleyDb\Facade
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