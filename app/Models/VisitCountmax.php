<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer           $id
 * @property integer           $mall_id
 * @property integer           $store_id
 * @property string            $number
 * @property \Carbon\Carbon    $deleted_at
 * @property \Carbon\Carbon    $created_at
 * @property \Carbon\Carbon    $updated_at
 * @property \App\Models\Mall  $mall
 * @property \App\Models\Store $store
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class VisitCountmax extends Model
{

    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'visit_countmax';

    /**
     * @var array
     */
    protected $fillable = [
        'mall_id',
        'store_id',
        'number',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'mall_id' => 'integer',
        'store_id' => 'integer',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


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
