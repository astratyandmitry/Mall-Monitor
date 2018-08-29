<?php

namespace App\Models;

/**
 * @property integer              $id
 * @property string               $name
 * @property float                $commission
 * @property integer              $mall_id
 * @property \App\Models\Mall     $mall
 * @property \App\Models\Cheque[] $cheques
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class Store extends Model
{

    /**
     * @var string
     */
    protected $table = 'stores';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'mall_id',
        'commission',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'mall_id' => 'integer',
        'commission' => 'float',
    ];

    /**
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:200',
        'mall_id' => 'required|exists:malls,id',
        'store_id' => 'required|exists:stores,id',
        'commission' => 'required|numeric',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'mall_id' => 'ТЦ',
        'commission' => 'комиссия',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mall(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Mall::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cheques(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Cheque::class);
    }


    /**
     * @return string
     */
    public function link(): string
    {
        return route('stores.show', $this->id);
    }

}
