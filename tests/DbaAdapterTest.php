<?php

declare(strict_types=1);

namespace AndKom\BerkeleyDb\Tests;

use AndKom\BerkeleyDb\Adapter\AdapterFactory;
use AndKom\BerkeleyDb\Adapter\AdapterInterface;

class DbaAdapterTest extends AdapterAbstractTest
{
    public function getAdapter(): AdapterInterface
    {
        return AdapterFactory::create('dba');
    }
}