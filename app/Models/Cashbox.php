<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property integer           $id
 * @property string            $code
 * @property integer           $mall_id
 * @property integer           $store_id
 * @property \Carbon\Carbon    $deleted_at
 * @property \App\Models\Store $store
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class Cashbox extends Model
{

    use \Illuminate\Database\Eloquent\SoftDeletes;

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
     * @var array
     */
    protected $dates = [
        'deleted_at',
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


    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeFilter(Builder $builder): Builder
    {
        $builder->with(['mall', 'store']);

        $builder->withTrashed();

        $builder->when(request('code'), function (Builder $builder): Builder {
            return $builder->where('code', 'LIKE', '%' . request('code') . '%');
        });

        $user = auth()->user();

        if ($user->mall_id) {
            $builder->where('mall_id', $user->mall_id);
        } else {
            $builder->when(request('mall_id'), function (Builder $builder): Builder {
                return $builder->where('mall_id', request('mall_id'));
            });
        }

        $builder->when(request('store_id'), function (Builder $builder): Builder {
            return $builder->where('store_id', request('store_id'));
        });

        return parent::scopeFilter($builder);
    }

}
