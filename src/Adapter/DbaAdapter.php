<?php

declare(strict_types=1);

namespace AndKom\BerkeleyDb\Adapter;

use AndKom\BerkeleyDb\Exception;

// check whether dba extension is loaded
if (!extension_loaded('dba')) {
    throw new \RuntimeException('Extension dba is not installed.');
}

// check whether db4 dba handler support
if (!in_array('db4', dba_handlers())) {
    throw new \RuntimeException('Handler db4 for dba is not installed.');
}

/**
 * Wrapper for db4 DBA handler
 * @package AndKom\BerkeleyDb\Adapter
 */
class DbaAdapter extends AbstractAdapter
{
    /**
     * @var resource
     */
    protected $db;

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
        if ($database) {
            throw new Exception('Database parameter is not supported by adapter.');
        }

        if ($this->db) {
            throw new Exception('Database is already opened.');
        }

        switch ($mode) {
            case 'r':
                $dbaMode = 'r';
                break;

            case 'w':
                $dbaMode = 'c';
                break;

            default:
                throw new Exception("Invalid open mode: $mode.");
        }


        $this->db = dba_open($filename, $dbaMode, 'db4');

        if (!$this->db) {
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

        dba_close($this->db);

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

        if (!dba_sync($this->db)) {
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

        return dba_fetch($key, $this->db);
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

        if (!dba_replace($key, $value, $this->db)) {
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

        return dba_delete($key, $this->db);
    }

    /**
     * @return string|bool
     * @throws Exception
     */
    public function firstKey()
    {
        $this->assertDatabaseOpened();

        return dba_firstkey($this->db);
    }

    /**
     * @return string|bool
     * @throws Exception
     */
    public function nextKey()
    {
        $this->assertDatabaseOpened();

        return dba_nextkey($this->db);
    }
}