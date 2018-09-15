<?php

declare(strict_types=1);

namespace AndKom\BerkeleyDb;

use AndKom\BerkeleyDb\Adapter\AdapterFactory;
use AndKom\BerkeleyDb\Adapter\AdapterInterface;

/**
 * Class Manager
 * @package AndKom\BerkeleyDb
 */
class Manager
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Manager constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->adapter = AdapterFactory::create($config['adapter']);
    }

    /**
     * @return AdapterInterface
     */
    public function adapter()
    {
        return $this->adapter;
    }

    /**
     * @return Manager
     * @throws Exception
     */
    public function maybeConnect(): self
    {
        if ($this->adapter->isOpen()) {
            return $this;
        }

        $this->adapter->open(
            $this->config['filename'],
            $this->config['mode'],
            $this->config['database']);

        return $this;
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws Exception
     */
    public function __call(string $method, array $parameters)
    {
        return $this->maybeConnect()->adapter()->$method(...$parameters);
    }
}