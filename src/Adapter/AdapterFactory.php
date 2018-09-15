<?php

declare(strict_types=1);

namespace AndKom\BerkeleyDb\Adapter;

/**
 * Class AdapterFactory
 * @package AndKom\BerkeleyDb\Adapter
 */
class AdapterFactory
{
    /**
     * @param string|null $adapter
     * @return AdapterInterface
     */
    public static function create(string $adapter = null): AdapterInterface
    {
        if (is_null($adapter)) {
            $adapter = static::getDefaultAdapter();
        }

        switch ($adapter) {
            case 'phpdb4':
                return new PhpDb4Adapter();

            case 'dba':
                return new DbaAdapter();
        }

        throw new \InvalidArgumentException("Unsupported adapter $adapter.");
    }

    /**
     * @return string
     */
    public static function getDefaultAdapter(): string
    {
        if (class_exists('\Db4')) {
            return 'phpdb4';
        } elseif (extension_loaded('dba') && in_array('db4', dba_handlers())) {
            return 'dba';
        } else {
            throw new \RuntimeException("Your PHP installation doesn't support Berkeley DB.");
        }
    }
}