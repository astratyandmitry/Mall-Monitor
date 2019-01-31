<?php

namespace App\Models;

/**
 * @property integer           $id
 * @property string            $code
 * @property integer           $mall_id
 * @property integer           $store_id
 * @property \App\Models\Store $store
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
        'mall_id',
        'store_id',
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
        'mall_id' => 'required|exists:malls,id',
        'store_id' => 'required|exists:stores,id',
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

}
