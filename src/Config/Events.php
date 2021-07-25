<?php

namespace Nfaiz\DbToolkit\Config;

use CodeIgniter\Events\Events;

Events::on('pre_system', function () {

	$config = config(DbToolkit::class);

	if ($config->boot === true)
	{
		service('dbtoolkit')->setAsGlobal();

        if ($config->collect === true)
        {
            service('dbtoolkit')->collect();
        }
	}

});