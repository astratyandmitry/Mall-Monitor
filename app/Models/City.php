<?php

namespace App\Models;

/**
 * @property integer             $id
 * @property string              $name
 * @property integer             $country_id
 * @property \App\Models\Country $country
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class City extends Model
{

    const ASTANA = 1;

    /**
     * @var string
     */
    protected $table = 'cities';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'country_id',
    ];

    /**e
     * @var array
     */
    protected $casts = [
        'country_id' => 'integer',
    ];

    /**
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:80',
        'country_id' => 'required|exists:countries,id',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'country_id' => 'страна',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

}
