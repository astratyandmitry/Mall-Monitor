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
class Role extends Model
{
    const ADMIN = 1;

    const MALL = 2;

    const TENANT = 3;

    /**
     * @var string
     */
    protected $table = 'roles';

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
