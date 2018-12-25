<?php

namespace App\Models;

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

}
