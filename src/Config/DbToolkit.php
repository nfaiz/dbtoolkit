<?php 

namespace Nfaiz\DbToolkit\Config;

use CodeIgniter\Config\BaseConfig;

class DbToolkit extends BaseConfig
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