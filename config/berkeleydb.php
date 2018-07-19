<?php

return [
    /**
     * Adapter to use: "phpdb4", "dba" or null.
     */
    'adapter' => null,

    /**
     * Path to database filename.
     */
    'filename' => database_path('database.dat'),

    /**
     * Database name (optional).
     */
    'database' => null,

    /**
     * Database open mode (r - read, w - write).
     */
    'mode' => 'w',
];