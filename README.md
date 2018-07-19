# PHP Berkeley DB

Simple Berkeley DB wrapper for PHP.

## Installation

**Download Berkeley DB source:**

```bash
wget 'http://download.oracle.com/berkeley-db/db-x.x.x.tar.gz'
tar -xzvf db-x.x.x.tar.gz
```

**Build Berkeley DB libraries and headers:** 

```bash
cd db-x.x.x/build_unix/
../dist/configure --enable-cxx
make
make install
```

**Install php_db4 extension for "phpdb4" adapter (better):**

Build db4 extension:

```bash
cd ../lang/php4_db
phpize
./configure --with-db4=/usr/local/BerkeleyDB.x.x
make
make install
```

Add db4 extension to php.ini:

```ini
extension=db4.so
```

**Or install db4 DBA handler for "dba" adapter:**

Build PHP with db4 support:

```bash
./configure --with-db4=/usr/local/BerkeleyDB.x.x
make
sudo make install
```

**Install composer package:**

```bash
composer install andkom/php-berkeley-db
```

## Usage

**Create instance:**

```PHP
use AndKom\PhpBerkeleyDb\Adapter\AdapterFactory;

$adapter = AdapterFactory::create(); // use first available adapter
$adapter = AdapterFactory::create("phpdb4"); // use phpdb4 adapter
$adapter = AdapterFactory::create("dba"); // use dba adapter
```

**Open database for reading:**

```PHP
$adapter->open("filename", "r");
```

**Open database with optional database name:**

```PHP
$adapter->open("filename", "r", "main");
```

**Open database for writing:**

```PHP
$adapter->open("filename", "w");
```

**Close database:**

```PHP
$adapter->close();
```

**Sync changes to database:**

```PHP
$adapter->sync();
```

**Read key value:**

```PHP
$value = $adapter->get("key");
```

**Write key value:**

```PHP
$adapter->put("key", "value");
```

**Delete key:**

```PHP
$adapter->delete("key");
```

**Returns first key and moves cursor to the next position:**

```PHP
$adapter->firstKey();
```

**Read next key and moves cursor to the next position:**

```PHP
$adapter->nextKey();
```

**Read all database keys:**

```PHP
foreach ($adapter->read() as $key => $value) {
    ...
}
```

### Laravel Service Provider

**Publish configuration to app config:**

```bash
./artisan vendor:publish --provider=AndKom\\PhpBerkeleyDb\\ServiceProvider
```

**Edit app/config/berkeleydb.php:**

```PHP
<?php
 
return [
    'adapter' => 'phpdb4',
 
    'filename' => database_path('database.dat'),
 
    'database' => null,
 
    'mode' => 'w',
];

```

**Use BDB facade to access database methods:**

```PHP
$value = BDB::get("key");
```