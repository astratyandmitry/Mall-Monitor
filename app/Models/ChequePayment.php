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
class ChequePayment extends Model
{
    const CASH = 1;

    const CARD = 2;

    const CREDIT = 3;

    const TARE = 4;

    const MIXED = 4;

    /**
     * @var string
     */
    protected $table = 'cheque_payments';

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
