<?php

namespace App\Classes\Graph;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class GraphDate
{
    /**
     * @var array
     */
    protected $options = [
        'daily' => 'created_date',
        'monthly' => 'created_yearmonth',
        'yearly' => 'created_year',
    ];

    /**
     * @var string
     */
    protected $defaultKey = 'daily';

    /**
     * @var string
     */
    protected $selectedKey;

    /**
     * @var null
     */
    protected static $instance = null;

    /**
     * @return void
     */
    public function __construct()
    {
        $selected = request()->query('graph_date_type', $this->defaultKey);
        $this->selectedKey = (array_key_exists($selected, $this->options)) ? $selected : $this->defaultKey;
    }

    /**
     * @return \App\Classes\GraphDate
     */
    public static function instance(): GraphDate
    {
        if (is_null(self::$instance)) {
            self::$instance = new GraphDate;
        }

        return self::$instance;
    }

    /**
     * @return string
     */
    public function getDateColumn(): string
    {
        return $this->options[$this->selectedKey];
    }

    /**
     * @return string
     */
    public function getStartedDate(): string
    {
        switch ($this->selectedKey) {
            case 'daily':
                return date('Y-m-d H:i:s', strtotime('-30 days'));
                break;
            case 'monthly':
                return date('Y-m-d H:i:s', strtotime('-12 months'));
                break;
            case 'yearly':
                return date('Y-m-d H:i:s', strtotime('-10 years'));
                break;
        }
    }
}
