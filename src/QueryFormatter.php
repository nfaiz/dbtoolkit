<?php

namespace Nfaiz\DbToolkit;

class QueryFormatter
{
    /**
     * Combines SQL and its bindings
     *
     * @return string
     */
    public function getSqlWithBindings(array $query): string
    {
        return vsprintf(str_replace('?', '%s', $query['query']), collect($query['bindings'])->map(function ($binding) {
            $binding = addslashes($binding);
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }

}