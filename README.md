![GitHub](https://img.shields.io/github/license/nfaiz/DbToolkit)
![GitHub repo size](https://img.shields.io/github/repo-size/nfaiz/ci4-DbToolkit-database?label=size)
![Hits](https://hits.seeyoufarm.com/api/count/incr/badge.svg?url=nfaiz/ci4-DbToolkit-database)

# DbToolkit
Illuminate Database Wrapper for CodeIgniter 4


## Description
An [Illuminate Database](https://github.com/illuminate/database) wrapper for **CodeIgniter 4** with **Query Collector Debug Toolbar**.


## Table of contents
  * [Requirement](#requirement)
  * [Installation](#installation)
  * [Setup](#setup)
    * [Create DbToolkit Config](#1-create-dbtoolkit-config)
    * [Modify Toolbar](#2-modify-toolbar)
  * [Configuration](#configuration)
    * [Prerequisite](#1-prerequisite)
      * [Pdo Extension](#i-pdo-extension)
      * [Database Connection](#ii-database-connection)
    * [DbToolkit config](#2-dbtoolkit-config)
    * [Toolbar Config](#3-toolbar-config)
      * [Query Collector](#i-query-collector)
      * [Styling](#ii-styling)
  * [Usage](#usage)
    * [Capsule](#capsule)
    * [Working With Database](#working-with-database)
      * [Query Builder](#1-using-query-builder)
      * [Schema Builder](#2-using-schema-builder)
      * [Eloquent ORM](#3-eloquent-orm)
  * [Credit](#credit)


## Requirement
* [Codeigniter 4](https://github.com/codeigniter4/CodeIgniter4)
* [Illuminate Database](https://github.com/illuminate/database)
* [Illuminate Events](https://github.com/illuminate/events)
* [Highlight.php](https://github.com/scrivo/highlight.php)


## Installation
Install library via composer:

    composer require nfaiz/DbToolkit


## Setup

### 1. Create DbToolkit Config
Library setup can be done via spark:

    php spark dbtoolkit:config

This command will;
* Create a new config file `app/Config/DbToolkit.php`.


### 2. Modify Toolbar
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

Once the capsule instance has been registered, the wrapper is ready to use. See [usage](#usage).


## Configuration

### 1.Prerequisite

#### i. PDO extension
* **Illuminate Database** is using **PDO** driver for database connection. Make sure selected **PDO** driver `extension` is enabled.
* This library will automatically **map** CodeIgniter 4 database connection setting to **Illuminate Database** PDO setting.

#### ii. Database connection
* Retain CodeIgniter 4 database connection setting.
* Refer [here](https://www.codeigniter.com/user_guide/database/configuration.html) how to configure database connection.
* Use connection group in **app/Config/Database.php** to set at **app/Config/DbToolkit.php** `$dbGroup` property.

DBDriver setting. [Reference](https://www.codeigniter.com/user_guide/intro/requirements.html).

|  Supported RDBMS | CI4 DBDriver (use this value) | Illuminate Database Driver |
| --- | --- | --- |
| MySQL 5.7+ | MySQLi | mysql |
| PostgreSQL 9.6+ | Postgre | pgsql |
| SQLite 3.8.8+ | SQLite3 | sqlite |
| SQL Server 2017+ | SQLSRV | sqlsrv |


E.g in .env
```dotsini
database.default.hostname = localhost
database.default.database = ci4
database.default.username = root
database.default.password = 
database.default.charset =
database.default.DBDriver = MySQLi
database.default.DBCollat =
database.default.DBPrefix =
```


### 2. DbToolkit Config
`File location: app/Config/DbToolkit.php`<br>

Change property value accordingly

| Property | Description | Default |
| --- | --- | --- |
| $boot | **DbToolkit** global instantiation | `true` |
| $collector | To enable/disable **Query Collector** | `true` |
| $dbGroup | Database connection group in **app/Config/Database.php** to use for **DbToolkit** instantiation. | `default` |
| $bootEloquent  | To enable/disable **Eloquent ORM** |  `false` |
| $eventDispatcher | To enable/disable **Event Dispather** (to use together with **Eloquent ORM**) |  `false` |
| $tabTitle | Title to display at debug toolbar tab | `string` | `DBQueries` |


### 3. Toolbar Config
`File location: app/Config/Toolbar.php`<br />

#### i. Query Collector
To display `DbToolkit` tab at `debug toolbar`. See configuration [here](docs/CONFIGURATION.md#toolbar-config)


#### ii. Styling
Change database toolbar styling. See configuration [here](docs/CONFIGURATION.md#database-toolbar-styling).


## Usage

### Capsule
* This library is using `capsule()` for database instance by default.<br />
* To add <em>alias</em> for `capsule()`, create `function()` in new or existing **helper** and load it in **app/Controllers/BaseController.php** or any other necessary place.<br />

E.g To add `DB()` as <em>alias</em> for `capsule()`
```php
<?php

if (! function_exists('DB'))
{
    function DB()
    {
        return service('DbToolkit')->capsule();
    }
}

```

### Working With Database

* [Query Builder](#1-using-query-builder)
* [Schema Builder](#2-using-schema-builder)
* [Eloquent ORM](#3-eloquent-orm)

#### 1. Using Query Builder
```php
$users = capsule()::table('users')->where('id', 1)->get();
```
Other core methods may be accessed directly from the `capsule()`
```php

$results = capsule()::select('select * from users where id = ? or username = ?', [1, 'nfaiz']);
```

Screenshot for `query string` at `DbToolkit` debug toolbar tab<br />
<img src="https://user-images.githubusercontent.com/1330109/126854504-e09a7429-c710-4611-bff4-d1373511803d.png" alt="1-query-builder" />

#### 2. Using Schema Builder
```php
capsule()::schema()->create('users', function ($table) {
    $table->increments('id');
    $table->string('email')->unique();
    $table->timestamps();
});
```

#### 3. Eloquent ORM
To begin using Eloquent ORM, make sure `$bootEloquent` is set to `true`. (**app/Config/DbToolkit.php**).


##### Basic usage

i. Model: `User`. `Table`: `users` [`PK`: `id`]
```PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
}
```

ii. Controller: `Home`.
```PHP
<?php

namespace App\Controllers;
use App\Models\User;

class Home extends BaseController
{
    public function index()
    {
        $users = User::All();

        $user = User::find(2);
    }
}

```

Screenshot for `query string` at `DbToolkit` debug toolbar tab<br />
<img src="https://user-images.githubusercontent.com/1330109/126854477-7ac28a4f-9dee-470d-91be-58fbb0095be0.png" alt="3-eloquent-orm-basic" />


##### Using Relationships

i. Model: `User`. `Table`: `users` [`PK`: `id`]
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function address()
    {
        return $this->hasOne(Address::class, 'userId', 'id');
    }
}
```

ii. Model: `Address`. `Table`: `addresses` [`PK`: `id`, `FK`: `userId`]
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
```

iii. Controller: `Home`.
```php
<?php

namespace App\Controllers;

use App\Models\User;

class Home extends BaseController
{
    public function index()
    {
        $user = User::find(1);
        $address = $user->address->getAttributes();
    }
}
```

Screenshot for `query string` at `DbToolkit` debug toolbar tab<br />
<img src="https://user-images.githubusercontent.com/1330109/126854456-3704ec55-11cb-4c5e-85b1-a1301546829e.png" alt="3-eloquent-orm-relationship" />

Take a look at [Illuminate Database](https://github.com/illuminate/database) page for more information.

## Credit
- [Laravel Component](https://github.com/illuminate)
- [Highlight.php](https://github.com/scrivo/highlight.php)
