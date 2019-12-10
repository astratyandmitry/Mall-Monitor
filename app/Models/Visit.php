<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer                   $id
 * @property integer                   $mall_id
 * @property integer                   $store_id
 * @property integer                   $countmax_id
 * @property integer                   $count
 * @property string                    $created_date
 * @property string                    $created_year
 * @property string                    $created_yearmonth
 * @property \Carbon\Carbon            $created_at
 * @property \App\Models\VisitCountmax $countmax
 * @property \App\Models\Mall          $mall
 * @property \App\Models\Store         $store
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class Visit extends Model
{

    /**
     * @var string
     */
    protected $table = 'visits';

    /**
     * @var array
     */
    protected $fillable = [
        'countmax_id',
        'store_id',
        'mall_id',
        'count',
        'created_at',
        'created_yearmonth',
        'created_year',
        'created_date',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'store_id' => 'integer',
        'mall_id' => 'integer',
        'countmax_id' => 'integer',
        'count' => 'integer',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;


    /**
     * @param string $value
     *
     * @return void
     */
    public function setCreatedAtAttribute(string $value): void
    {
        $datetime = strtotime($value);

        $this->attributes['created_at'] = date('Y-m-d H:i:s', $datetime);
        $this->attributes['created_date'] = date('Y-m-d', $datetime);
        $this->attributes['created_yearmonth'] = date('Y-m', $datetime);
        $this->attributes['created_year'] = date('Y', $datetime);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mall(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function countmax(): BelongsTo
    {
        return $this->belongsTo(VisitCountmax::class);
    }

}
