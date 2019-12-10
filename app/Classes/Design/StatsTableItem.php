<?php

namespace App\Classes\Design;

use stdClass;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class StatsTableItem
{

    /**
     * @var \stdClass
     */
    protected $stats;

    /**
     * @var int
     */
    protected $visitsCount = 0;


    /**
     * StatsTableItem constructor.
     *
     * @param \stdClass $stats
     * @param int|null  $visitsCount
     */
    public function __construct(stdClass $stats, ?int $visitsCount = null)
    {
        $this->stats = $stats;
        $this->visitsCount = (int)$visitsCount;
    }


    /**
     * @return string
     */
    public function getDateFormatted(): string
    {
        return date('d.m.Y', strtotime($this->stats->date));
    }


    /**
     * @return int
     */
    public function getVisitsCount(): int
    {
        return $this->visitsCount;
    }


    /**
     * @return int
     */
    public function getChequesCount(): int
    {
        return (int)$this->stats->count;
    }


    /**
     * @return int
     */
    public function getChequesAmount(): int
    {
        return (int)$this->stats->amount;
    }


    /**
     * @return int
     */
    public function getChequesAvgAmount(): int
    {
        return round($this->getChequesAmount() / $this->getChequesCount());
    }


    /**
     * @return float
     */
    public function getConversion(): float
    {
        $chequesCount = $this->getChequesCount();

        return ($chequesCount > 0 && $this->visitsCount > 0) ? round($chequesCount * 100 / $this->visitsCount, 2) : (float)0;
    }

}
