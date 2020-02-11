<?php

namespace App\Classes\Design;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class StatsTableTotal
{

    /**
     * @var int
     */
    protected $visitsCount = 0;

    /**
     * @var int
     */
    protected $chequesCount = 0;

    /**
     * @var int
     */
    protected $chequesAmount = 0;


    /**
     * @param \App\Classes\Design\StatsTableItem $tableItem
     *
     * @return void
     */
    public function increase(StatsTableItem $tableItem): void
    {
        $this->visitsCount += $tableItem->getVisitsCount();
        $this->chequesCount += $tableItem->getChequesCount();
        $this->chequesAmount += $tableItem->getChequesAmount();
    }


    /**
     * @return int
     */
    public function getCountVisits(): int
    {
        return $this->visitsCount;
    }


    /**
     * @return int
     */
    public function getChequesCount(): int
    {
        return $this->chequesCount;
    }


    /**
     * @return int
     */
    public function getChequesAmount(): int
    {
        return $this->chequesAmount;
    }


    /**
     * @return int
     */
    public function getChequesAvgAmount(): int
    {
        return $this->chequesAmount && $this->chequesCount ? round($this->chequesAmount / $this->chequesCount) : 0;
    }

}
