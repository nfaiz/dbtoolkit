# Manual

## Installation

* [Download and set autoload](MANUAL.md#1-Download-and-set-autoload)
* [Install Illuminate Database](MANUAL.md#2-install-illuminate-database)
* [Install Illuminate Events](MANUAL.md#3-install-illuminate-events)
* [Install Highlight.php](MANUAL.md#4-install-highlightphp)

### 1. Download and set autoload
Download this library, extract and rename this folder to **ci4-debug-toolbar**.
Enable it by editing **app/Config/Autoload.php** and adding the **Nfaiz\DbToolkit** namespace to the **$psr4** array.<br />
See [namespace](https://www.codeigniter.com/user_guide/general/modules.html#namespaces) for more information.

E.g Using **app/ThirdParty** directory path:
```php

$psr4 = [
    APP_NAMESPACE     => APPPATH, // For custom app namespace
    'Config'          => APPPATH . 'Config',
    'Nfaiz\DbToolkit' => APPPATH . 'ThirdParty\ci4-illuminate-database\src',
];
```

### 2. Install Illuminate Database
Install package via composer:

    composer require illuminate/database:^8

### 3. Install Illuminate Events
Install package via composer:

    composer require illuminate/events:^8

### 4. Install Highlight.php
Install package via composer:

    composer require scrivo/highlight.php:^v9.18

Back to [<< Installation Section](../README.md#installation).

## Setup

In **app/Config** directory<br />

Modify file [Toolbar.php](MANUAL.md#toolbar)

Create file [DbToolkit.php](MANUAL.md#dbtoolkit)


### Toolbar
Open `app/Config/Toolbar.php`<br />

#### i. Add DbToolkit class collector
**Add** `\Nfaiz\DbToolkit\Collectors\Database::class` item in **$collectors** `array`.

```php

public $collectors = [
    Timers::class,
    Database::class,
    \Nfaiz\DbToolkit\Collectors\Database::class,
    Logs::class,
    Views::class,
    // \CodeIgniter\Debug\Toolbar\Collectors\Cache::class,
    Files::class,
    Routes::class,
    Events::class,
];
```

#### ii. Add sqlCssTheme and sqlMarginBottom property
```php
public $maxQueries = 100;

/**
 * -------------------------------------------------------------
 * SQL CSS Theme
 * -------------------------------------------------------------
 * 
 * Configuration for light and dark mode SQL syntax highlighter.
 *
 * @var array
 */
public $sqlCssTheme = [
    'light' => 'default',
    'dark'  => 'dark'
];

/**
 * -------------------------------------------------------------
 * Bottom Margin Between Queries
 * -------------------------------------------------------------
 * 
 * Value in px
 * 
 * @var int
 */
public $sqlMarginBottom = 4;
```

### DbToolkit
Create `app/Config/DbToolkit.php` and **extends** `\Nfaiz\DbToolkit\Config\DbToolkit`<br />

```php
<?php

namespace Config;

class DbToolkit extends extends \Nfaiz\DbToolkit\Config\DbToolkit
{
    /**
     * -------------------------------------------------------------
     * Boot
     * -------------------------------------------------------------
     * 
     * To enable or disable Illuminate Database
     * 
     * @var boolean
     */
    public $boot = true;

    /**
     * -------------------------------------------------------------
     * Query Collector
     * -------------------------------------------------------------
     * 
     * To enable or disable query collector
     * 
     * @var boolean
     */
    public $collect = true;

    /**
     * -------------------------------------------------------------
     * DB Group
     * -------------------------------------------------------------
     * 
     * DB group from app/Config/Database.php to use for connection.
     * 
     * @var string
     */
    public $dbGroup = 'default';

    /**
     * -------------------------------------------------------------
     * Boot Eloquent
     * -------------------------------------------------------------
     * 
     * To enable Eloquent ORM.
     * 
     * @var boolean
     */
    public $bootEloquent = false;

    /**
     * -------------------------------------------------------------
     * Event Dispacther
     * -------------------------------------------------------------
     * 
     * Enable Event Dispacther.
     * Set to true will overwrite $bootEloquent to true.
     * 
     * @var boolean
     */
     public $eventDispatcher = false;

    /**
     * -------------------------------------------------------------
     * Tab Title
     * -------------------------------------------------------------
     * 
     * Tab title display
     * 
     * @var string
     */
    public $tabTitle = 'DBQueries';

    /**
     * -------------------------------------------------------------
     * Database Parser Template
     * -------------------------------------------------------------
     * 
     * Database parser template location.
     * 
     * @var string
     */
    public $dbTemplate = 'Nfaiz\DbToolkit\Views\database.tpl';
}
```

Back to [<< Setup Section](../README.md#setup).