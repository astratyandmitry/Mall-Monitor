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
     * @var array
     */
    public static $options = [
        self::SELLF,
        self::SELL_RETURN,
        self::BUY,
        self::BUY_RETURN,
        self::DEPOSIT,
        self::WITHDRAWAL,
    ];

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


    /**
     * @return string
     */
    public function getCssClass(): string
    {
        switch ($this->id) {
            case ChequeType::SELL:
            case ChequeType::BUY_RETURN:
                return 'is-success';
                break;
            case ChequeType::SELL_RETURN:
            case ChequeType::BUY:
                return 'is-danger';
                break;
            case ChequeType::DEPOSIT:
            case ChequeType::WITHDRAWAL:
                return 'is-warning';
                break;
        }

        return '';
    }

}
