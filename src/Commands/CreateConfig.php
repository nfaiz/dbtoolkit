<?php

namespace Nfaiz\DbToolkit\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Autoload;

class CreateConfig extends BaseCommand
{
    protected $group = 'DbToolkit';

    protected $name = 'dbtoolkit:config';

    protected $description = 'Create DbToolkit Config File.';

    protected $usage = 'dbtoolkit:config';

    public function run(array $params)
    {
        $this->createConfig();
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