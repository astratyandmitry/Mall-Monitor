<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer            $id
 * @property integer            $mall_id
 * @property integer            $store_id
 * @property array              $config
 * @property  \App\Models\Mall  $mall
 * @property  \App\Models\Store $store
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class StoreIntegration extends Model
{

    /**
     * @var string
     */
    protected $table = 'store_integrations';

    /**
     * @var array
     */
    protected $fillable = [
        'mall_id',
        'store_id',
        'config',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'mall_id' => 'integer',
        'store_id' => 'integer',
        'config' => 'array',
    ];

    /**
     * @var array
     */
    protected $rules = [
        'mall_id' => 'required|exists:malls,id',
        'store_id' => 'required|exists:stores,id',
        'config' => 'required',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $messages = [
        'config' => 'конфигурация',
        'config.*' => 'конфигурация',
        'mall_id' => 'ТЦ',
        'store_id' => 'Арендатор',
    ];


    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeFilter(Builder $builder): Builder
    {
        $builder->with(['mall', 'store']);

        $builder->when(request('store_id'), function (Builder $builder): Builder {
            return $builder->where('store_id', request('store_id'));
        });

        $builder->when(request('mall_id'), function (Builder $builder): Builder {
            return $builder->where('mall_id', request('mall_id'));
        });

        return parent::scopeFilter($builder);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mall(): BelongsTo
    {
        return $this->belongsTo(Mall::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

}
