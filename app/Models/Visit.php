<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer                   $id
 * @property integer                   $countmax_id
 * @property integer                   $count
 * @property \Carbon\Carbon            $fixed_at
 * @property \App\Models\VisitCountmax $countmax
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
        'count',
        'fixed_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
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

}
