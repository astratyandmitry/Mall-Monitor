<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer                           $id
 * @property integer                           $mall_id
 * @property integer                           $store_id
 * @property integer                           $type_id
 * @property array                             $config
 * @property array                             $columns
 * @property array                             $types
 * @property array                             $payments
 * @property  \App\Models\Mall                 $mall
 * @property  \App\Models\Store                $store
 * @property  \App\Models\StoreIntegrationType $type
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class StoreIntegration extends Model
{

    /**
     * @var string
     */
    protected $table = 'store_integrations';

    /**
     * @var array
     */
    protected $fillable = [
        'mall_id',
        'store_id',
        'type_id',
        'config',
        'columns',
        'types',
        'payments',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'mall_id' => 'integer',
        'store_id' => 'integer',
        'type_id' => 'integer',
        'config' => 'array',
        'columns' => 'array',
        'types' => 'array',
        'payments' => 'array',
    ];

    /**
     * @var array
     */
    protected $rules = [
        'mall_id' => 'required|exists:malls,id',
        'store_id' => 'required|exists:stores,id',
        'type_id' => 'required|exists:store_integration_types,id',
        '_unique' => 'store_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $messages = [
        'config' => 'конфигурация',
        'config.*' => 'конфигурация',
        'mall_id' => 'ТЦ',
        'store_id' => 'арендатор',
        'type_id' => 'тип',
    ];


    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeFilter(Builder $builder): Builder
    {
        $builder->with(['mall', 'store']);

        $builder->when(request('store_id'), function (Builder $builder): Builder {
            return $builder->where('store_id', request('store_id'));
        });

        $builder->when(request('mall_id'), function (Builder $builder): Builder {
            return $builder->where('mall_id', request('mall_id'));
        });

        $builder->when(request('type_id'), function (Builder $builder): Builder {
            return $builder->where('type_id', request('type_id'));
        });

        return parent::scopeFilter($builder);
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
    public function type(): BelongsTo
    {
        return $this->belongsTo(StoreIntegrationType::class);
    }


    /**
     * @return array
     */
    public static function getFields(): array
    {
        return [
            'kkm_code' => 'Код кассы',
            'code' => 'Уникальный номер в системе',
            'number' => 'Номер чека',
            'amount' => 'Сумма чека',
            'type_id' => 'Тип операции',
            'payment_id' => 'Вид платежа',
            'created_at' => 'Дата и время чека',
            'created_at_date' => 'Дата чека',
            'created_at_time' => 'Время чека',
        ];
    }
}
