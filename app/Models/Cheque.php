<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $kkm_code
 * @property string $code
 * @property string $number
 * @property string $shift_number
 * @property float $amount
 * @property string $data
 * @property integer $mall_id
 * @property integer $store_id
 * @property integer $cashbox_id
 * @property integer $type_id
 * @property integer $payment_id
 * @property string $created_date
 * @property string $created_year
 * @property string $created_yearmonth
 * @property \Carbon\Carbon $created_at
 * @property \App\Models\Mall $mall
 * @property \App\Models\Store $store
 * @property \App\Models\Cashbox $cashbox
 * @property \App\Models\ChequeType $type
 * @property \App\Models\ChequePayment $payment
 * @property \App\Models\ChequeItem[] $items
 *
 * @method static Builder uniqueAttrs(int $store_id, array $attrs)
 * @method static Builder reportMall(?string $dateFrom, ?string $dateTo)
 * @method static Builder reportStore(?string $dateFrom, ?string $dateTo)
 * @method static Builder reportDetail(?string $dateFrom, ?string $dateTo)
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class Cheque extends Model
{
    /**
     * @var string
     */
    protected $table = 'cheques';

    /**
     * @var array
     */
    protected $fillable = [
        'kkm_code',
        'code',
        'number',
        'shift_number',
        'amount',
        'data',
        'mall_id',
        'store_id',
        'cashbox_id',
        'type_id',
        'payment_id',
        'created_at',
        'created_yearmonth',
        'created_year',
        'created_date',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'amount' => 'float',
        'data' => 'array',
        'mall_id' => 'integer',
        'store_id' => 'integer',
        'cashbox_id' => 'integer',
        'type_id' => 'integer',
        'payment_id' => 'integer',
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
     * @var array
     */
    protected $rules = [
        'kkm_code' => 'required|max:200',
        'code' => 'required|max:200',
        'number' => 'required|max:200',
        'shift_number' => 'nullable|max:80',
        'amount' => 'required|numeric',
        'data' => 'nullable',
        'mall_id' => 'required|exists:malls,id',
        'store_id' => 'required|exists:stores,id',
        'cashbox_id' => 'required|exists:cashboxes,id',
        'type_id' => 'required|exists:cheque_types,id',
        'payment_id' => 'required|exists:cheque_payments,id',
        'created_at' => 'required',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'kkm_code' => 'ККМ код',
        'code' => 'код',
        'number' => 'номер',
        'shift_number' => 'номер смены',
        'amount' => 'сумма',
        'mall_id' => 'ТЦ',
        'store_id' => 'заведение',
        'cashbox_id' => 'касса',
        'type_id' => 'тип транзакции',
        'payment_id' => 'вид платежа',
        'data' => 'данные',
        'created_at' => 'время',
    ];

    /**
     * @param string $value
     *
     * @return void
     */
    public function setCreatedAtAttribute(string $value): void
    {
        $datetime = strtotime(explode(',', $value)[0]);

        $this->attributes['created_at'] = date('Y-m-d H:i:s', $datetime);
        $this->attributes['created_date'] = date('Y-m-d', $datetime);
        $this->attributes['created_yearmonth'] = date('Y-m', $datetime);
        $this->attributes['created_year'] = date('Y', $datetime);
    }

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cashbox(): BelongsTo
    {
        return $this->belongsTo(Cashbox::class)->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ChequeType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(ChequePayment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(ChequeItem::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int $store_id
     * @param array $attrs
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeUniqueAttrs(Builder $builder, int $store_id, array $attrs): Builder
    {
        foreach ($attrs as $key => $value) {
            if ($key === 'created_at') {
                $builder->whereDate($key, date('Y-m-d', strtotime($value)));
            } else {
                $builder->where($key, $value);
            }
        }

        $builder->where('store_id', $store_id);

        return $builder;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string|null $dateFrom
     * @param string|null $dateTo
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeReportDetail(
        Builder $builder,
        ?string $dateFrom = null,
        ?string $dateTo = null
    ): Builder {
        $builder->with(['store', 'payment', 'type']);

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

        $sort_key = request()->query('sort_key', 'created_at');
        $sort_type = request()->query('sort_type', 'desc');

        if (! \Schema::hasColumn($builder->getModel()->getTable(), $sort_key)) {
            $sort_key = 'created_at';
        }

        if (! in_array($sort_type, ['asc', 'desc'])) {
            $sort_type = 'asc';
        }

        $builder->when(request('cashbox_id'), function (Builder $builder): Builder {
            return $builder->where('cashbox_id', request('cashbox_id'));
        });

        $builder->orderBy($sort_key, $sort_type);

        return $builder;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string|null $dateFrom
     * @param string|null $dateTo
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

        if ($sort = request()->query('sort')) {
            if ($limit = request()->query('limit')) {
                $builder->limit($limit);
            }

            switch ($sort) {
                case 'top_up_count':
                    $builder->orderBy('count', 'desc');
                    break;
                case 'top_down_count':
                    $builder->orderBy('count', 'asc');
                    break;
                case 'top_up_amount':
                    $builder->orderBy('amount', 'desc');
                    break;
                case 'top_down_amount':
                    $builder->orderBy('amount', 'asc');
                    break;
                case 'top_up_avg':
                    $builder->orderBy('avg', 'desc');
                    break;
                case 'top_down_avg':
                    $builder->orderBy('avg', 'asc');
                    break;
            }
        } else {
            $sort_key = request()->query('sort_key', 'created_at');
            $sort_type = request()->query('sort_type', 'desc');

            if (! \Schema::hasColumn($builder->getModel()->getTable(), $sort_key)) {
                $sort_key = 'created_at';
            }

            if (! in_array($sort_type, ['asc', 'desc'])) {
                $sort_type = 'asc';
            }

            $builder->orderBy($sort_key, $sort_type);
        }

        $builder->when(request('cashbox_id'), function (Builder $builder): Builder {
            return $builder->where('cashbox_id', request('cashbox_id'));
        });

        return $builder;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string|null $dateFrom
     * @param string|null $dateTo
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

        $sort_key = request()->query('sort_key', 'mall_id');
        $sort_type = request()->query('sort_type', 'asc');

        if (! \Schema::hasColumn($builder->getModel()->getTable(), $sort_key) && ! in_array($sort_key, [
                'avg',
                'count',
                'amount',
            ])) {
            $sort_key = 'mall_id';
        }

        if (! in_array($sort_type, ['asc', 'desc'])) {
            $sort_type = 'asc';
        }

        $builder->orderBy($sort_key, $sort_type);

        $builder->whereIn('store_id', Store::query()->pluck('id')->toArray());

        $builder->when(request('cashbox_id'), function (Builder $builder): Builder {
            return $builder->where('cashbox_id', request('cashbox_id'));
        });

        return $builder;
    }
}
