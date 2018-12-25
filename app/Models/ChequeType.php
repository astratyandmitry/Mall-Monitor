<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string  $name
 * @property string  $system_key
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ChequeType extends Model
{

    const SELL = 1;
    const SELL_RETURN = 2;
    const BUY = 3;
    const BUY_RETURN = 4;
    const DEPOSIT = 5;
    const WITHDRAWAL = 6;

    /**
     * @var string
     */
    protected $table = 'cheque_types';

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
