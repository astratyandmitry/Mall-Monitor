<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property integer              $id
 * @property string               $name
 * @property integer              $city_id
 * @property \App\Models\City     $city
 * @property \App\Models\Cheque[] $cheques
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class Mall extends Model
{

    const KERUEN_CITY = 1;

    /**
     * @var string
     */
    protected $table = 'malls';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'city_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'city_id' => 'integer',
    ];

    /**
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:200',
        'city_id' => 'required|exists:cities,id',
        '_unique' => 'name',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'city_id' => 'город',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cheques(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Cheque::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function integrations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MallIntegration::class);
    }


    /**
     * @param int $system_id
     *
     * @return \App\Models\MallIntegration|null
     */
    public function getIntegration(int $system_id): ?MallIntegration
    {
        return $this->integrations()->where('system_id', $system_id)->first();
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeFilter(Builder $builder): Builder
    {
        $builder->with(['city']);

        $builder->when(request('name'), function (Builder $builder): Builder {
            return $builder->where('name', 'LIKE', '%' . request('name') . '%');
        });

        $builder->when(request('city_id'), function (Builder $builder): Builder {
            return $builder->where('city_id', request('city_id'));
        });

        return parent::scopeFilter($builder);
    }
}
