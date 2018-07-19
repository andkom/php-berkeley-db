<?php

declare(strict_types=1);

namespace AndKom\PhpBerkeleyDb\Tests;

use AndKom\PhpBerkeleyDb\Adapter\AdapterFactory;
use AndKom\PhpBerkeleyDb\Adapter\AdapterInterface;

class PhpDb4AdapterTest extends AdapterAbstractTest
{
    public function getAdapter(): AdapterInterface
    {
        return AdapterFactory::create('phpdb4');
    }
}