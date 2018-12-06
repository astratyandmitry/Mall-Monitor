<?php

namespace App\Models;

/**
 * @property integer                   $id
 * @property string                    $kkm_code
 * @property string                    $code
 * @property string                    $number
 * @property float                     $amount
 * @property string                    $data
 * @property integer                   $mall_id
 * @property integer                   $store_id
 * @property integer                   $type_id
 * @property integer                   $payment_id
 * @property \Carbon\Carbon            $created_at
 * @property \App\Models\Mall          $mall
 * @property \App\Models\Store         $store
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
        'amount',
        'data',
        'mall_id',
        'store_id',
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
        'amount' => 'required|numeric',
        'data' => 'nullable',
        'mall_id' => 'required|exists:malls,id',
        'store_id' => 'required|exists:stores,id',
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
        'amount' => 'сумма',
        'mall_id' => 'ТЦ',
        'store_id' => 'заведение',
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
        return $this->belongsTo(Store::class);
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
     * @param null|string $created_at
     */
    public function setCreatedAtAttribute(?string $created_at): void
    {
        $this->attributes['created_at'] = date('Y-m-d H:i:s', strtotime(explode(',', $created_at)[0]));
    }

}
