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
class XMLChequeTransformer extends ChequeTransformer
{
    /**
     * @var \SimpleXMLElement
     */
    protected $item;

    /**
     * @param \SimpleXMLElement $item
     *
     * @return \App\Integration\Store\XMLChequeTransformer
     */
    public function setItem(\SimpleXMLElement $item): XMLChequeTransformer
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

        return @$this->item->{$key};
    }

    /**
     * @return string
     */
    protected function getDateAttribute(): string
    {
        if (! $this->integration) {
            return date('Y-m-d H:i:s', strtotime(@$this->item->created_at));
        }

        if ($this->integration->config['created_at']) {
            return date('Y-m-d H:i:s', strtotime(@$this->item->created_at));
        }

        $dateKey = $this->integration->config['created_at_date'];

        if ($this->integration->config['created_at_time']) {
            $timeKey = $this->integration->config['created_at_time'];

            $date = @$this->item->{$dateKey}.' '.@$this->item->{$timeKey};
            $date = str_replace('.', '-', $date);

            if (strtotime($date) !== false) {
                $date = date('Y-m-d H:i:s', strtotime($date));
            }

            return $date;
        }

        return date('Y-m-d', strtotime(@$this->item->{$dateKey})).' 12:00:00';
    }
}
