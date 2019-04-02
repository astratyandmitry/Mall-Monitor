<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property integer             $id
 * @property string              $name
 * @property string              $color
 * @property \App\Models\Store[] $store
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class StoreType extends Model
{

    const DEFAULT = 1;

    /**
     * @var string
     */
    protected $table = 'store_types';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'color',
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
        'color' => 'required|max:10',
        '_unique' => 'name',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'color' => 'цвет',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stores(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Store::class, 'type_id');
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeFilter(Builder $builder): Builder
    {
        $builder->when(request('name'), function (Builder $builder): Builder {
            return $builder->where('name', 'LIKE', '%' . request('name') . '%');
        });

        return parent::scopeFilter($builder);
    }

}
