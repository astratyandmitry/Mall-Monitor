<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string $name
 * @property string $system_key
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class MallIntegrationSystem extends Model
{
    const PROSYSTEMS = 1;

    const WEBKASSA = 2;

    const TRINITY  = 3;

    const PROSKLAD  = 4;

    /**
     * @var string
     */
    protected $table = 'mall_integration_systems';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'system_key',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:80',
        'system_key' => 'required|max:40',
        '_unique' => 'name|system_key',
    ];
}
