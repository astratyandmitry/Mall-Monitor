<?php

namespace App\Models;

/**
 * @property integer           $id
 * @property string            $code
 * @property float             $amount
 * @property string            $data
 * @property integer           $mall_id
 * @property integer           $store_id
 * @property \Carbon\Carbon    $created_at
 * @property \App\Models\Mall  $mall
 * @property \App\Models\Store $store
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class Cheque extends Model
{

    /**
     * @var string
     */
    protected $table = 'cheques';

    /**
     * @var array
     */
    protected $fillable = [
        'code',
        'amount',
        'data',
        'mall_id',
        'store_id',
        'created_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'amount' => 'float',
        'data' => 'array',
        'mall_id' => 'integer',
        'store_id' => 'integer',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $rules = [
        'code' => 'required|max:200',
        'amount' => 'required|numeric',
        'data' => 'nullable',
        'mall_id' => 'required|exists:malls,id',
        'store_id' => 'required|exists:stores,id',
        'created_at' => 'sometimes',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'code' => 'код/номер',
        'amount' => 'сумма',
        'mall_id' => 'ТЦ',
        'store_id' => 'заведение',
        'data' => 'данные',
        'created_at' => 'время',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mall(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Mall::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class);
    }


    /**
     * @param null|string $created_at
     */
    public function setCreatedAtAttribute(?string $created_at): void
    {
        $this->attributes['created_at'] = date('Y-m-d H:i:s', strtotime(($created_at) ? $created_at : 'NOW'));
    }

}
