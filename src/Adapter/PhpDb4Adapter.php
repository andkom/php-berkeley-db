<?php

declare(strict_types=1);

namespace AndKom\PhpBerkeleyDb\Adapter;

use AndKom\PhpBerkeleyDb\Exception;

// check whether php_db4 extension is loaded
if (!class_exists('\Db4')) {
    throw new \RuntimeException('Extension db4 is not installed.');
}

// fix php_db4 db type constant bug
if (!defined('DB_BTREE')) {
    define('DB_BTREE', 1);
}

/**
 * Wrapper for ext-db4
 * @package AndKom\PhpBerkeleyDb\Adapter
 */
class PhpDb4Adapter extends AbstractAdapter
{
    /**
     * @var \Db4
     */
    protected $db;

    /**
     * @var \Db4Cursor
     */
    protected $cursor;

    /**
     * @return void
     * @throws Exception
     */
    protected function assertDatabaseOpened()
    {
        if (!$this->db) {
            throw new Exception('Database is not opened yet.');
        }
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return !!$this->db;
    }

    /**
     * @param string $filename
     * @param string $mode
     * @param string|null $database
     * @return AdapterInterface
     * @throws Exception
     */
    public function open(string $filename, string $mode, string $database = null): AdapterInterface
    {
        if ($this->db) {
            throw new Exception('Database is already opened.');
        }

        switch ($mode) {
            case 'r':
                $flags = DB_RDONLY;
                break;

            case 'w':
                $flags = DB_CREATE;
                break;

            default:
                throw new Exception("Invalid open mode: $mode.");
        }

        $dir = sys_get_temp_dir();

        $env = new \Db4Env();

        // buggy db4 doesn't return true on success
        if ($env->open($dir) === false) {
            throw new Exception("Unable to open tmp dir: $dir.");
        }

        $this->db = new \Db4($env);

        if (!$this->db->open(null, $filename, (string)$database, DB_BTREE, $flags)) {
            throw new Exception("Unable to open database: $filename.");
        }

        return $this;
    }

    /**
     * @return AdapterInterface
     * @throws Exception
     */
    public function close(): AdapterInterface
    {
        $this->assertDatabaseOpened();

        if ($this->cursor) {
            $this->cursor->close();
            $this->cursor = null;
        }

        $this->db->close();
        $this->db = null;

        return $this;
    }

    /**
     * @return AdapterInterface
     * @throws Exception
     */
    public function sync(): AdapterInterface
    {
        $this->assertDatabaseOpened();

        if (!$this->db->sync()) {
            throw new Exception('Unable to sync database.');
        }

        return $this;
    }

    /**
     * @param string $key
     * @return string|bool
     * @throws Exception
     */
    public function get(string $key)
    {
        $this->assertDatabaseOpened();

        return $this->db->get($key);
    }

    /**
     * @param string $key
     * @param string $value
     * @return AdapterInterface
     * @throws Exception
     */
    public function put(string $key, string $value): AdapterInterface
    {
        $this->assertDatabaseOpened();

        if (!$this->db->put($key, $value)) {
            throw new Exception("Unable to set key: $key to a value: $value.");
        }

        return $this;
    }

    /**
     * @param string $key
     * @return bool
     * @throws Exception
     */
    public function delete(string $key): bool
    {
        $this->assertDatabaseOpened();

        return $this->db->del($key) === 0;
    }

    /**
     * @return string|bool
     * @throws Exception
     */
    public function firstKey()
    {
        $this->assertDatabaseOpened();

        if ($this->cursor) {
            $this->cursor->close();
        }

        $this->cursor = $this->db->cursor();

        return $this->nextKey();
    }

    /**
     * @return string|bool
     * @throws Exception
     */
    public function nextKey()
    {
        if (!$this->cursor) {
            return false;
        }

        $this->assertDatabaseOpened();

        $key = $value = null;

        if ($this->cursor->get($key, $value) == 0) {
            return $key;
        }

        $this->cursor->close();
        $this->cursor = null;

        return false;
    }
}