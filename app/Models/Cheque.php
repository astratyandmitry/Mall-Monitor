<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property integer                   $id
 * @property string                    $kkm_code
 * @property string                    $code
 * @property string                    $number
 * @property string                    $shift_number
 * @property float                     $amount
 * @property string                    $data
 * @property integer                   $mall_id
 * @property integer                   $store_id
 * @property integer                   $cashbox_id
 * @property integer                   $type_id
 * @property integer                   $payment_id
 * @property \Carbon\Carbon            $created_at
 * @property \App\Models\Mall          $mall
 * @property \App\Models\Store         $store
 * @property \App\Models\Cashbox       $cashbox
 * @property \App\Models\ChequeType    $type
 * @property \App\Models\ChequePayment $payment
 * @property \App\Models\ChequeItem[]  $items
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mall(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Mall::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class)->withTrashed();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cashbox(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cashbox::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ChequeType::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ChequePayment::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ChequeItem::class);
    }


    /**
     * @param null|string $created_at
     */
    public function setCreatedAtAttribute(?string $created_at): void
    {
        $this->attributes['created_at'] = date('Y-m-d H:i:s', strtotime(explode(',', $created_at)[0]));
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string|null                           $dateFrom
     * @param string|null                           $dateTo
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeReportDetail(Builder $builder, ?string $dateFrom = null, ?string $dateTo = null): Builder
    {
        $builder->with(['store', 'payment', 'type']);

        $builder->where('mall_id', auth()->user()->mall_id);

        $builder->when(request('mall_id'), function (Builder $builder): Builder {
            return $builder->where('mall_id', request('mall_id'));
        });

        $builder->when(request()->query('store_id'), function ($builder) {
            return $builder->where('store_id', request()->query('store_id'));
        });

        $builder->when(request('cashbox_id'), function (Builder $builder): Builder {
            return $builder->where('cashbox_id', request('cashbox_id'));
        });

        if ($dateFrom) {
            $builder->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('created_at', '<=', $dateTo);
        }

        $sort_key = request()->query('sort_key', 'created_at');
        $sort_type = request()->query('sort_type', 'desc');

        if ( ! \Schema::hasColumn($builder->getModel()->getTable(), $sort_key)) {
            $sort_key = 'created_at';
        }

        if ( ! in_array($sort_type, ['asc', 'desc'])) {
            $sort_type = 'asc';
        }

        $builder->orderBy($sort_key, $sort_type);

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
        $builder->where('mall_id', auth()->user()->mall_id);

        $builder->when(request('mall_id'), function (Builder $builder): Builder {
            return $builder->where('mall_id', request('mall_id'));
        });

        $builder->when(request()->query('store_id'), function ($builder) {
            return $builder->where('store_id', request()->query('store_id'));
        });

        if ($dateFrom) {
            $builder->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('created_at', '<=', $dateTo);
        }

        $sort_key = request()->query('sort_key', 'store_id');
        $sort_type = request()->query('sort_type', 'asc');

        if ( ! \Schema::hasColumn($builder->getModel()->getTable(), $sort_key) && ! in_array($sort_key, ['avg', 'count', 'amount'])) {
            $sort_key = 'store_id';
        }

        if ( ! in_array($sort_type, ['asc', 'desc'])) {
            $sort_type = 'asc';
        }

        $builder->orderBy($sort_key, $sort_type);

        return $builder;
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
        $builder->where('mall_id', auth()->user()->mall_id);

        $builder->when(request('mall_id'), function (Builder $builder): Builder {
            return $builder->where('mall_id', request('mall_id'));
        });

        if ($dateFrom) {
            $builder->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('created_at', '<=', $dateTo);
        }

        $sort_key = request()->query('sort_key', 'mall_id');
        $sort_type = request()->query('sort_type', 'asc');

        if ( ! \Schema::hasColumn($builder->getModel()->getTable(), $sort_key) && ! in_array($sort_key, ['avg', 'count', 'amount'])) {
            $sort_key = 'mall_id';
        }

        if ( ! in_array($sort_type, ['asc', 'desc'])) {
            $sort_type = 'asc';
        }

        $builder->orderBy($sort_key, $sort_type);

        return $builder;
    }

}
