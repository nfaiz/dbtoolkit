<?php

namespace Nfaiz\DbToolkit;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

class connection
{
    protected $capsule;

    protected $config;

    function __construct()
    {
        $this->capsule = new Capsule();
    }

    protected function getCapsule()
    {
        if ($this->config->boot === true)
        {
            return $this->capsule;
        }

        throw new \Exception("Not boot up. Set to true \$boot property at 'app/Config/DbToolkit.php' to enable it."); 

    }

    protected function boot()
    {
        helper('dbtoolkit');

        $this->config = config(DbToolkit::class);

        $this->addConnection();

        $this->enableBootEloquent($this->config);

        $this->enableEventDispatcher($this->config);
    }

    /**
     * Enable Query Collector
     */
    protected function collectSql()
    {
        return $this->capsule::enableQueryLog();
    }

    /**
     * Map and add connection from from database connection setting to DbToolkit Database setting.
     */
    private function addConnection()
    {
        $config = config(Database::class);

        $db = $this->getDbGroup($config, $this->config);

        if (! isset($config->{$db}))
        {
           throw new \Exception("No DB Group found in app/Config/Database.php using '{$db}'.");
        }

        $driver = $this->getDbDriver($config->{$db}['DBDriver']);

        $this->capsule->addConnection([
            'driver'    => $driver,
            'host'      => $config->{$db}['hostname'],
            'database'  => $config->{$db}['database'],
            'username'  => $config->{$db}['username'],
            'password'  => $config->{$db}['password'],
            'charset'  =>  $config->{$db}['charset'],
            'collation' => $config->{$db}['DBCollat'],
            'prefix'    => $config->{$db}['DBPrefix']
        ]);
    }

    /**
     * Map DBDriver from database connection to DbToolkit Database PDO driver
     *
     * @return string
     */
    private function getDbDriver(string $driver): string
    {
        switch ($driver) 
        {
            case 'MySQLi':
                $driver = 'mysql';
                break;
            case 'Postgre':
                $driver = 'pgsql';
                break;
            case 'SQLite3':
                $driver = 'sqlite';
                break;
            case 'SQLSRV':
                $driver = 'sqlsrv';
                break;
          
            default:
                throw new \Exception("DB Driver '{$driver}' Not Supported. Please Use CI4 DB driver name");
                break;
        }

        return $driver;
    }

    /**
     * Get DBGroup name from DbToolkit Config. Use defaultGroup from CI Database Config if not found.
     *
     * @return string
     */
    private function getDbGroup(object $config, object $DbToolkitConfig): string
    {
        return $DbToolkitDbConfig->dbGroup ?? $config->defaultGroup;
    }

    /**
     * Enable Query Collector
     */
    private function enableBootEloquent(object $config)
    {
        if (isset($config->bootEloquent) && $config->bootEloquent === true)
        {
            $this->capsule->bootEloquent();
        }
    }

    /**
     * Enable Event Dispatcher
     */
    private function enableEventDispatcher(object $config)
    {
        if (isset($config->eventDispatcher) && $config->eventDispatcher === true)
        {
            $this->capsule->setEventDispatcher(new Dispatcher(new Container));
            
            $this->capsule->bootEloquent();
        }
    }
}