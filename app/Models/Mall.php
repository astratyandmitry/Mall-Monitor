<?php

namespace App\Models;

/**
 * @property integer          $id
 * @property string           $name
 * @property integer          $city_id
 * @property \App\Models\City $city
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class Mall extends Model
{

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

}
