<?php

namespace Nfaiz\DbToolkit\Collectors;

use CodeIgniter\Debug\Toolbar\Collectors\BaseCollector;
use Nfaiz\DbToolkit\Formatter;
use Nfaiz\DbToolkit\QueryFormatter;

class Database extends BaseCollector
{
    /**
     * Whether this collector has data that can
     * be displayed in the Timeline.
     *
     * @var boolean
     */
    protected $hasTimeline = true;

    /**
     * Whether this collector needs to display
     * content in a tab or not.
     *
     * @var boolean
     */
    protected $hasTabContent = true;

    /**
     * Whether this collector has data that
     * should be shown in the Vars tab.
     *
     * @var boolean
     */
    protected $hasVarData = false;

    /**
     * The 'title' of this Collector.
     * Used to name things in the toolbar HTML.
     *
     * @var string
     */
    protected $title;

    protected $config;

    public function __construct()
    {
        $this->config = config(DbToolkit::class);

        $config = config(DbToolkit::class);

        $this->title = $config->tabTitle;
    }

    /**
     * Returns timeline data formatted for the toolbar.
     *
     * @return array The formatted data or an empty array.
     */
    protected function formatTimelineData(): array
    {
        $data = [];

        foreach (capsule()::getQueryLog() as $query)
        {
            $data[] = [
                'name'      => 'Query',
                'component' => 'Database',
                'start'     => microtime(true),
                'duration'  => (float) $query['time'] / 1000,
            ];
        }

        return $data;
    }

    /**
     * Returns the data of this collector to be formatted in the toolbar
     *
     * @return array
     */
    public function display(): string   
    {
        if ($this->config->boot !== true)
        {
            return '';
        }

        $hl = new Formatter();
        $qf = new QueryFormatter();

        $queries = [];

        foreach (capsule()::getQueryLog() as $query)
        {
            $queries[] = [
                'duration' => $query['time'] . ' ms',
                'sql'      => $hl->highlightSql($qf->getSqlWithBindings($query))
            ];
        }

        return $hl->render($queries, $this->config->dbTemplate);
    }

    /**
     * Gets the "badge" value for the button.
     *
     * @return int
     */
    public function getBadgeValue(): int
    {
        return ($this->config->boot === true) ? count(capsule()::getQueryLog()) : 0;
    }

    /**
     * Information to be displayed next to the title.
     *
     * @return string The number of queries (in parentheses) or an empty string.
     */
    public function getTitleDetails(): string
    {
        return ($this->config->boot === true) ? '(' . count(capsule()::getQueryLog()) . ' Queries'  . ')' : '';
    }

    /**
     * Display the icon.
     *
     * Icon from https://icons8.com - 1em package
     *
     * @return string
     */
    public function icon(): string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADMSURBVEhLY6A3YExLSwsA4nIycQDIDIhRWEBqamo/UNF/SjDQjF6ocZgAKPkRiFeEhoYyQ4WIBiA9QAuWAPEHqBAmgLqgHcolGQD1V4DMgHIxwbCxYD+QBqcKINseKo6eWrBioPrtQBq/BcgY5ht0cUIYbBg2AJKkRxCNWkDQgtFUNJwtABr+F6igE8olGQD114HMgHIxAVDyAhA/AlpSA8RYUwoeXAPVex5qHCbIyMgwBCkAuQJIY00huDBUz/mUlBQDqHGjgBjAwAAACexpph6oHSQAAAAASUVORK5CYII=';
    }
}