<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
 * @method static Builder reportMall(?string $dateFrom, ?string $dateTo)
 * @method static Builder reportStore(?string $dateFrom, ?string $dateTo)
 * @method static Builder reportDetail(?string $dateFrom, ?string $dateTo)
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
        return $this->belongsTo(Store::class)->withTrashed();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function countmax(): BelongsTo
    {
        return $this->belongsTo(VisitCountmax::class);
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string|null                           $dateFrom
     * @param string|null                           $dateTo
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeReportMall(Builder $builder, ?string $dateFrom = null, ?string $dateTo = null): Builder
    {
        $user = auth()->user();

        if ($user->mall_id) {
            $builder->where('mall_id', $user->mall_id);
        } else {
            $builder->when(request('mall_id'), function (Builder $builder): Builder {
                return $builder->where('mall_id', request('mall_id'));
            });
        }

        if ($dateFrom) {
            $builder->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('created_at', '<=', $dateTo);
        }

        return $builder;
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string|null                           $dateFrom
     * @param string|null                           $dateTo
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeReportStore(Builder $builder, ?string $dateFrom = null, ?string $dateTo = null): Builder
    {
        $user = auth()->user();

        if ($user->mall_id) {
            $builder->where('mall_id', $user->mall_id);
        } else {
            $builder->when(request('mall_id'), function (Builder $builder): Builder {
                return $builder->where('mall_id', request('mall_id'));
            });
        }

        if ($user->store_id) {
            $builder->where('store_id', $user->store_id);
        } else {
            $builder->whereIn('store_id', Store::report()->pluck('id'));
        }

        if ($dateFrom) {
            $builder->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('created_at', '<=', $dateTo);
        }

        return $builder;
    }

}
