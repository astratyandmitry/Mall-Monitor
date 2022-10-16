<?php

namespace App\Integration\Store;

use App\Models\Cashbox;
use App\Models\Developer;
use App\Models\ChequeType;
use App\Models\ChequePayment;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ExcelChequeTransformer extends ChequeTransformer
{
    /**
     * @var array
     */
    protected $item;

    /**
     * @param array $item
     *
     * @return \App\Integration\Store\ExcelChequeTransformer
     */
    public function setItem(array $item): ExcelChequeTransformer
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected function getAttribute(string $key)
    {
        $key = ($this->integration) ? @$this->integration->config[$key] : $key;

        return @$this->item[$key];
    }

    /**
     * @return string
     */
    protected function getDateAttribute(): string
    {
        if (! $this->integration) {
            return date('Y-m-d H:i:s', strtotime(@$this->item['created_at']));
        }

        if ($this->integration->config['created_at']) {
            return date('Y-m-d H:i:s', strtotime(@$this->item['created_at']));
        }

        $dateKey = $this->integration->config['created_at_date'];

        if ($this->integration->config['created_at_time']) {
            $timeKey = $this->integration->config['created_at_time'];

            return date('Y-m-d H:i:s', strtotime(@$this->item[$dateKey].' '.@$this->item[$timeKey]));
        }

        return date('Y-m-d', strtotime(@$this->item[$dateKey])).' 12:00:00';
    }
}
