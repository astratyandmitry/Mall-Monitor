<?php

namespace App\Models;

/**
 * @property integer                 $id
 * @property string                  $code
 * @property integer                 $type_id
 * @property integer                 $store_id
 * @property \App\Models\CashboxType $type
 * @property \App\Models\Store       $store
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class Cashbox extends Model
{

    /**
     * @var string
     */
    protected $table = 'cashboxes';

    /**
     * @var array
     */
    protected $fillable = [
        'code',
        'type_id',
        'store_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'type_id' => 'integer',
        'store_id' => 'integer',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $rules = [
        'code' => 'required|max:120',
        'type_id' => 'required|exists:cashbox_types,id',
        'store_id' => 'required|exists:stores,id',
        '_unique' => 'code',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'code' => 'код/номер',
        'type_id' => 'тип',
        'store_id' => 'заведение',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CashboxType::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

}
