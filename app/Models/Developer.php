<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer           $id
 * @property string            $username
 * @property string            $password
 * @property integer           $mall_id
 * @property integer           $store_id
 * @property \Carbon\Carbon    $deleted_at
 * @property \App\Models\Mall  $mall
 * @property \App\Models\Store $store
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class Developer extends Model implements Authorizable, Authenticatable
{

    use \Illuminate\Foundation\Auth\Access\Authorizable, \Illuminate\Auth\Authenticatable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'developers';

    /**
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'mall_id',
        'store_id',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'mall_id' => 'integer',
        'store_id' => 'integer',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $rules = [
        'username' => 'required|max:80',
        'mall_id' => 'required|exists:malls,id',
        'store_id' => 'required|exists:stores,id',
        '_unique' => 'username',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'username' => 'логин',
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

        $builder->withTrashed();

        $builder->when(request('username'), function (Builder $builder): Builder {
            return $builder->where('username', 'LIKE', '%' . request('username') . '%');
        });

        $builder->when(request('store_id'), function (Builder $builder): Builder {
            return $builder->where('store_id', request('store_id'));
        });

        $builder->when(request('mall_id'), function (Builder $builder): Builder {
            return $builder->where('mall_id', request('mall_id'));
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
        return $this->belongsTo(Store::class)->withTrashed();
    }

}
