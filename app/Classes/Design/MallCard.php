<?php

namespace App\Classes\Design;

use stdClass;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class MallCard
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
     * @var int
     */
    protected $amount = 0;


    /**]
     * MallItem constructor.
     *
     * @param \stdClass|null $stats
     * @param int|null       $visitsCount
     */
    public function __construct(?stdClass $stats = null, ?int $visitsCount = null)
    {
        $this->stats = $stats;
        $this->visitsCount = (int)$visitsCount;
    }


    /**
     * @return int
     */
    public function getChequesAmount(): int
    {
        return ! is_null($this->stats) ? round($this->stats->amount) : 0;
    }


    /**
     * @return int
     */
    public function getChequesCount(): int
    {
        return ! is_null($this->stats) ? $this->stats->count : 0;
    }


    /**
     * @return int
     */
    public function getVisitsCount(): int
    {
        return $this->visitsCount;
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
