<?php

declare(strict_types=1);

namespace AndKom\PhpBerkeleyDb\Tests;

use AndKom\PhpBerkeleyDb\Adapter\AdapterInterface;
use PHPUnit\Framework\TestCase;

abstract class AdapterAbstractTest extends TestCase
{
    abstract protected function getAdapter(): AdapterInterface;

    public function testRead()
    {
        $adapter = $this->getAdapter();
        $adapter->open(__DIR__ . '/data/test.dat', 'w');

        $this->assertEquals($adapter->get('unexistent'), false);
        $this->assertEquals($adapter->get('test'), 'hello world');
        $this->assertEquals($adapter->nextKey(), false);
        $this->assertEquals($adapter->firstKey(), 'test');
        $this->assertEquals($adapter->nextKey(), 'test 2');
        $this->assertEquals($adapter->nextKey(), false);

        $data = iterator_to_array($adapter->read());

        $this->assertArrayHasKey('test', $data);
        $this->assertArrayHasKey('test 2', $data);

        $this->assertEquals($data['test'], 'hello world');
        $this->assertEquals($data['test 2'], 'hello world 2');

        $adapter->close();
    }

    public function testWrite()
    {
        $adapter = $this->getAdapter();
        $adapter->open(__DIR__ . '/data/test_write.dat', 'w');

        $this->assertEquals($adapter->get('test'), false);

        $adapter->put('test', 'hello world')->sync();

        $this->assertEquals($adapter->get('test'), 'hello world');

        $this->assertEquals($adapter->delete('test'), true);
        $this->assertEquals($adapter->delete('test123'), false);
        $adapter->sync();
        $this->assertEquals($adapter->get('test'), false);

        $adapter->close();
    }

    public function tearDown()
    {
        @unlink(__DIR__ . '/data/test_write.dat');
    }
}