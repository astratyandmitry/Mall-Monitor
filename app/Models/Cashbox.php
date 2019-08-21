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
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        static::updated(function (Cashbox $cashbox): void {
            if ($cashbox->wasChanged('store_id')) {
                \DB::table('cheques')->where('cashbox_id', $cashbox->id)->update([
                    'mall_id' => $cashbox->store->mall_id,
                    'store_id' => $cashbox->store->id,
                ]);
            }
        });
    }


    /**
     * @param \App\Models\Store $store
     *
     * @return string
     */
    public static function generateCodeFor(Store $store): string
    {
        $mallIndex = str_pad($store->mall_id, 2, '0', STR_PAD_LEFT);
        $storeIndex = str_pad($store->id, 5, '0', STR_PAD_LEFT);

        return "AACN_{$mallIndex}_{$storeIndex}";
    }


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
        return $this->belongsTo(Store::class)->withTrashed();
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


        $builder->when(request('filter'), function (Builder $builder): Builder {
            switch (request('filter')) {
                case 1:
                    $builder->whereNull('deleted_at');
                    break;
                case 2:
                    $builder->whereNotNull('deleted_at');
                    break;
            }

            return $builder;
        });

        return parent::scopeFilter($builder);
    }

}
