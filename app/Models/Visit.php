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
 * @property \Carbon\Carbon            $fixed_at
 * @property \App\Models\VisitCountmax $countmax
 * @property \App\Models\Mall
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
        'fixed_at',
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
        'fixed_at',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;


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
