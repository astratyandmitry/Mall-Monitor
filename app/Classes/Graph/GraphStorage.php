<?php

namespace App\Classes\Graph;

use App\DateHelper;
use App\Models\Store;

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
     * @var array
     */
    protected $colors = [
        "#000000",
        "#ffebcd",
        "#0000ff",
        "#8a2be2",
        "#a52a2a",
        "#deb887",
        "#5f9ea0",
        "#7fff00",
        "#d2691e",
        "#ff7f50",
        "#6495ed",
        "#dc143c",
        "#00ffff",
        "#00008b",
        "#008b8b",
        "#b8860b",
        "#a9a9a9",
        "#006400",
        "#a9a9a9",
        "#bdb76b",
        "#8b008b",
        "#556b2f",
        "#ff8c00",
        "#9932cc",
        "#8b0000",
        "#e9967a",
        "#8fbc8f",
        "#483d8b",
        "#00ced1",
        "#9400d3",
        "#ff1493",
        "#00bfff",
        "#1e90ff",
        "#b22222",
        "#228b22",
        "#ff00ff",
        "#daa520",
        "#ffd700",
        "#808080",
        "#008000",
        "#adff2f",
        "#808080",
        "#ff69b4",
        "#cd5c5c",
        "#4b0082",
        "#7cfc00",
        "#fffacd",
        "#add8e6",
        "#f08080",
        "#90ee90",
        "#d3d3d3",
        "#ffb6c1",
        "#ffa07a",
        "#20b2aa",
        "#87cefa",
        "#778899",
        "#32cd32",
        "#faf0e6",
        "#ff00ff",
        "#800000",
        "#66cdaa",
        "#0000cd",
        "#ba55d3",
        "#9370db",
        "#3cb371",
        "#7b68ee",
        "#00fa9a",
        "#48d1cc",
        "#c71585",
        "#191970",
        "#808000",
        "#6b8e23",
        "#ffa500",
        "#ff4500",
        "#da70d6",
        "#98fb98",
        "#afeeee",
        "#db7093",
        "#ffdab9",
        "#cd853f",
        "#ffc0cb",
        "#b0e0e6",
        "#800080",
        "#663399",
        "#ff0000",
        "#bc8f8f",
        "#4169e1",
        "#8b4513",
        "#fa8072",
        "#f4a460",
        "#2e8b57",
        "#fff5ee",
        "#a0522d",
        "#c0c0c0",
        "#87ceeb",
        "#6a5acd",
        "#708090",
        "#fffafa",
        "#00ff7f",
        "#4682b4",
        "#d2b48c",
        "#008080",
        "#d8bfd8",
        "#ff6347",
        "#40e0d0",
        "#ee82ee",
        "#ffff00",
        "#9acd32",
    ];

    /**
     * @param string $key
     * @param mixed $value
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
     * @param mixed $data_key
     * @param mixed $data_index
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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

        $month = DateHelper::getMonthAbbr((int) $dates[1]);

        if (count($dates) == 2) {
            return "{$month} {$dates[0]}";
        }

        $day = DateHelper::getDayAbbr(date('N', strtotime($date)));

        return (int) $dates[2]." {$month} {$dates[0]} ({$day})";
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $names
     *
     * @return array
     */
    public function getSeriesMultiData(array $names): array
    {
        $data = $this->data;

        $series = [
            'count' => [],
            'amount' => [],
            'avg' => [],
            'visits' => [],
        ];

        if (isset($data['count']) && count($data['count'])) {
            foreach ($data['count'] as $storeId => $item) {
                $series['count'][] = [
                    'name' => $names[$storeId],
                    'data' => array_values($item),
                ];
            }
        }

        if (isset($data['amount']) && count($data['amount'])) {
            foreach ($data['amount'] as $storeId => $item) {
                $series['amount'][] = [
                    'name' => $names[$storeId],
                    'data' => array_values($item),
                ];
            }
        }

        if (isset($data['avg']) && count($data['avg'])) {
            foreach ($data['avg'] as $storeId => $item) {
                $series['avg'][] = [
                    'name' => $names[$storeId],
                    'data' => array_values($item),
                ];
            }
        }

        if (isset($data['visits']) && count($data['visits'])) {
            foreach ($data['visits'] as $storeId => $item) {
                $series['visits'][] = [
                    'name' => $names[$storeId],
                    'data' => array_values($item),
                ];
            }
        }

        return $series;
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
