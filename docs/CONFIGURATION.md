## Configuration

### Toolbar Config
Open **app/Config/Toolbar.php**.

To **Enable/Disable** DbToolkit debug toolbar tab, **uncomment/comment**`\Nfaiz\DbToolkit\Collectors\Database::class` in **$collectors** array.

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

Back to [<< Events Configuration Section](../README.md#toolbar-config).

### Database Toolbar Styling
Open **app/Config/Toolbar.php**.

Find `$sqlCssTheme` and `$sqlMarginBottom` property.

```php

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

* For `$sqlMarginBottom` set value using integer. This is for margin between queries.
* For `$sqlCssTheme`, `light` and `dark` are mode options for CodeIgniter 4 debug toolbar.
* Assign stylesheet name without `.css` extension. E.g `'github'`

Available stylesheets can be found using HighlightUtilities function from Highlight.php.

Please see [highlighter-utilities](https://github.com/scrivo/highlight.php#highlighter-utilities) for more information.

E.g In **Controller**

```php

// Get available stylesheets.
$list = \HighlightUtilities\getAvailableStyleSheets();
d($list);

// Set true to get available stylesheets with absolute path.
$listPath = \HighlightUtilities\getAvailableStyleSheets(true);
d($listPath);

// Get specific stylesheet path.
$path = \HighlightUtilities\getStyleSheetPath('github');
d($path);
```

Back to [<< Highlighter Configuration Section](../README.md#styling).