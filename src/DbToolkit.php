<?php

namespace Nfaiz\DbToolkit;

class DbToolkit extends Connection
{
    function __construct()
    {
        parent::__construct();

        $this->boot();
    }

    // Get Capsule
    public function capsule()
    {
        return $this->getCapsule();
    }

    // Query Collector
    public function collect()
    {
        $this->collectSql();
    }

    // Make it globally accessible
    public function setAsGlobal()
    {
        $this->getCapsule()->setAsGlobal();
    }
}