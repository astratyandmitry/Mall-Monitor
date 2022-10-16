<?php

namespace App\Classes\Date;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ReportDate
{
    /**
     * @var string
     */
    protected $defaultGroup = 'date';

    /**
     * @var string
     */
    protected $group;

    /**
     * @var \App\Classes\ReportDate
     */
    protected static $instance = null;

    /**
     * @var string|null
     */
    protected $dateFrom;

    /**
     * @var string|null
     */
    protected $dateTo;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->setup();
    }

    /**
     * @return  void
     */
    protected function setup(): void
    {
        $this->dateFrom = $this->getFromRequest('from');
        $this->dateTo = $this->getFromRequest('to');

        $diff = date_diff(date_create($this->dateFrom), date_create($this->dateTo));
        $group = 'date';

        if ((! $this->dateFrom && ! $this->dateTo) || (int) $diff->format("%Y") > 0) {
            $group = 'year';
        } elseif ((int) $diff->format("%m") > 0) {
            $group = 'yearmonth';
        }

        $this->group = $group;
    }

    /**
     * @return \App\Classes\Date\ReportDate
     */
    public static function instance(): ReportDate
    {
        if (is_null(self::$instance)) {
            self::$instance = new ReportDate;
        }

        return self::$instance;
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function getFromRequest(string $key): ?string
    {
        if ($date = request()->query("date_{$key}")) {
            $time = request()->query("time_{$key}");

            if (! $time) {
                $time = ($key == 'from') ? '00:00' : '23:59';
            }

            request()->merge([
                "time_{$key}" => $time,
            ]);

            return date('Y-m-d H:i:s', strtotime("{$date} {$time}"));
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getDateFrom(): ?string
    {
        return $this->dateFrom;
    }

    /**
     * @return string|null
     */
    public function getDateTo(): ?string
    {
        return $this->dateTo;
    }

    /**
     * @return string
     */
    public function getDateGroup(): string
    {
        return $this->group;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [$this->dateFrom, $this->dateTo, $this->group];
    }

    /**
     * @return string
     */
    public function stringify(): string
    {
        $dateFrom = date('d.m.Y H:i', strtotime($this->dateFrom));
        $dateTo = date('d.m.Y H:i', strtotime($this->dateTo));

        if ($this->dateFrom && $this->dateTo) {
            return "c {$dateFrom} по {$dateTo}";
        }

        if ($this->dateFrom) {
            return "c {$dateFrom}";
        }

        if ($this->dateTo) {
            return "по {$dateTo}";
        }

        return 'За все время';
    }
}
