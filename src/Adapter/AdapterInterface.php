<?php

declare(strict_types=1);

namespace AndKom\PhpBerkeleyDb\Adapter;

use AndKom\PhpBerkeleyDb\Exception;

/**
 * Interface AdapterInterface
 *
 * @package AndKom\PhpBerkeleyDb\Adapter
 */
interface AdapterInterface
{
    /**
     * @return bool
     */
    public function isOpen(): bool;

    /**
     * @param string $filename
     * @param string $mode
     * @param string|null $database
     * @return AdapterInterface
     * @throws Exception
     */
    public function open(string $filename, string $mode, string $database = null): self;

    /**
     * @return AdapterInterface
     * @throws Exception
     */
    public function close(): self;

    /**
     * @return AdapterInterface
     * @throws Exception
     */
    public function sync(): self;

    /**
     * @param string $key
     * @return string|bool
     * @throws Exception
     */
    public function get(string $key);

    /**
     * @param string $key
     * @param string $value
     * @return AdapterInterface
     * @throws Exception
     */
    public function put(string $key, string $value): self;

    /**
     * @param string $key
     * @return bool
     * @throws Exception
     */
    public function delete(string $key): bool;

    /**
     * @return string|bool
     * @throws Exception
     */
    public function firstKey();

    /**
     * @return string|bool
     * @throws Exception
     */
    public function nextKey();

    /**
     * Read database keys.
     * @return \Generator
     */
    public function read(): \Generator;
}