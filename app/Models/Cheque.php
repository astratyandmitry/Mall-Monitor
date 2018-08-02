<?php

namespace App\Models;

/**
 * @property integer             $id
 * @property string              $code
 * @property float               $amount
 * @property string              $data
 * @property integer             $cashbox_id
 * @property \Carbon\Carbon      $created_at
 * @property \App\Models\Cashbox $cashbox
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
        'code',
        'amount',
        'data',
        'cashbox_id',
        'created_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'amount' => 'float',
        'data' => 'array',
        'cashbox_id' => 'integer',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $rules = [
        'code' => 'required|max:200',
        'amount' => 'required|numeric',
        'data' => 'nullable',
        'cashbox_id' => 'required|exists:cashboxes,id',
        'created_at' => 'sometimes',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'code' => 'код/номер',
        'amount' => 'сумма',
        'cashbox_id' => 'касса',
        'data' => 'данные',
        'created_at' => 'время',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cashbox(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cashnox::class);
    }


    /**
     * @param null|string $created_at
     */
    public function setCreatedAtAttribute(?string $created_at): void
    {
        $this->attributes['created_at'] = date('Y-m-d H:i:s', strtotime(($created_at) ? $created_at : 'NOW'));
    }

}
