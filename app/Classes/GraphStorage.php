<?php

namespace App\Classes;

use App\DateHelper;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class GraphStorage
{

    const LABEL = 'labels';
    const AMOUNT = 'amount';
    const COUNT = 'count';
    const AVG = 'avg';
    const VISITS = 'visits';

    /**
     * @var array
     */
    protected $data = [];


    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return \App\Classes\GraphStorage
     */
    public function addMultiLabel(string $key, $value): GraphStorage
    {
        $this->data[self::LABEL][$key] = $this->formatDate($value);

        return $this;
    }


    /**
     * @param string $key
     * @param mixed  $data_key
     * @param mixed  $data_index
     * @param mixed  $value
     *
     * @return \App\Classes\GraphStorage
     */
    public function addMultiValue(string $key, $data_key, $data_index, $value): GraphStorage
    {
        $this->data[$key][$data_key][$data_index] = round($value);

        return $this;
    }


    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return \App\Classes\GraphStorage
     */
    public function addValue(string $key, $value): GraphStorage
    {
        $this->data[$key][] = $value;

        return $this;
    }


    /**
     * @param mixed $value
     *
     * @return \App\Classes\GraphStorage
     */
    public function addValueLabel($value): GraphStorage
    {
        return $this->addValue(self::LABEL, $this->formatDate($value));
    }


    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return \App\Classes\GraphStorage
     */
    public function addNumberValue(string $key, $value): GraphStorage
    {
        return $this->addValue($key, round($value));
    }


    /**
     * @param mixed $value
     *
     * @return \App\Classes\GraphStorage
     */
    public function addValueAmount($value): GraphStorage
    {
        return $this->addNumberValue(self::AMOUNT, $value);
    }


    /**
     * @param mixed $value
     *
     * @return \App\Classes\GraphStorage
     */
    public function addValueCount($value): GraphStorage
    {
        return $this->addNumberValue(self::COUNT, $value);
    }


    /**
     * @param mixed $value
     *
     * @return \App\Classes\GraphStorage
     */
    public function addValueAvg($value): GraphStorage
    {
        return $this->addNumberValue(self::AVG, $value);
    }


    /**
     * @param string $date
     *
     * @return string
     */
    protected function formatDate(string $date): string
    {
        $dates = explode('-', $date);

        if (count($dates) == 1) {
            return $date;
        }

        $month = DateHelper::getMonthAbbr((int)$dates[1]);

        if (count($dates) == 2) {
            return "{$month} {$dates[0]}";
        }

        $day = DateHelper::getDayAbbr(date('N', strtotime($date)));

        return (int)$dates[2] . " {$month} {$dates[0]} ({$day})";
    }


    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }


    /**
     * @return array
     */
    public function getReverseData(): array
    {
        $data = [];

        foreach ($this->data as $key => $value) {
            $data[$key] = array_reverse($value);
        }

        return $data;
    }

}
