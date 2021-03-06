<?php

namespace Nfaiz\DbToolkit\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Autoload;

class Publish extends BaseCommand
{
    protected $group = 'DbToolkit';

    protected $name = 'dbtoolkit:publish';

    protected $description = 'Publish DbToolkit and Toolbar Config File. (Experimental)';

    protected $usage = 'dbtoolkit:publish';

    public function run(array $params)
    {
        $this->createConfig();

        $this->modifyToolbar();
    }

    protected function createConfig()
    {
        $sourcePath = realpath(__DIR__ . '/../');

        if ($sourcePath == '/' || empty($sourcePath))
        {
            CLI::error('Invalid Directory');
            exit();
        }

        $sourcePath .= DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR;

        $filename = 'DbToolkit.php';

        $content = file_get_contents($sourcePath . $filename);

        $content = str_replace('namespace Nfaiz\DbToolkit\Config', "namespace Config", $content);

        $content = str_replace("use CodeIgniter\Config\BaseConfig;" . PHP_EOL . PHP_EOL, '', $content);

        $content = str_replace('extends BaseConfig', "extends \Nfaiz\DbToolkit\Config\DbToolkit", $content);

        $this->writeConfigFile($filename, $content);
    }

    protected function modifyToolbar()
    {
        $filename = 'Toolbar.php';

        $content = file_get_contents($this->getAppConfiGPath($filename));

        $content = str_replace(
            "Timers::class," . PHP_EOL . "        \Nfaiz\DbToolkit\Collectors\Database::class,", 
            "Timers::class,", 
            $content
        );

        $content = str_replace(
            "Timers::class,", 
            "Timers::class," . PHP_EOL . "        \Nfaiz\DbToolkit\Collectors\Database::class,", 
            $content
        );

        $content = str_replace(
            service('parser')->render('Nfaiz\DbToolkit\Views\sqlcsstheme.tpl'), 
            '}', 
            $content
        );

        $content = str_replace(
            "}", 
            service('parser')->render('Nfaiz\DbToolkit\Views\sqlcsstheme.tpl'), 
            $content
        );

        $this->writeConfigFile($filename, $content);
    }

    protected function getAppConfiGPath($filename)
    {
        $config = config(Autoload::class);

        return $config->psr4['Config'] . DIRECTORY_SEPARATOR . $filename;
    }

    protected function writeConfigFile(string $filename, string $content)
    {
        $file = $this->getAppConfiGPath($filename); 
        
        $directory = dirname($file);

        if (! is_dir($directory))
        {
            mkdir($directory, 0777, true);
        }

        if (file_exists($file))
        {
            $overwrite = (bool) CLI::getOption('f');

            if (! $overwrite && CLI::prompt("File '{$filename}' already exists in app/Config. Overwrite?", ['n', 'y']) === 'n')
            {
                CLI::error("Skipped {$filename}.");
                return;
            }
        }

        if (write_file($file, $content))
        {
            CLI::write(CLI::color('Created: ', 'green') . $filename);
        }
        else
        {
            CLI::error("Error creating {$filename}.");
        }
    }
}