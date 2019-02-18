<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string  $name
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class StoreType extends Model
{

    const DEFAULT = 1;

    /**
     * @var string
     */
    protected $table = 'store_types';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
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
        '_unique' => 'name',
    ];

}
