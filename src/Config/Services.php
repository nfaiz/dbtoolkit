<?php 

namespace Nfaiz\DbToolkit\Config;

use Config\Services as BaseService;

class Services extends BaseService
{
    public static function dbtoolkit($getShared = true)
    {
        if ($getShared)
        {
            return static::getSharedInstance('dbtoolkit');
        }

        return new \Nfaiz\DbToolkit\DbToolkit();
    }
}