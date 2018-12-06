<?php

namespace App\Models;

/**
 * @property integer        $id
 * @property integer        $cheque_id
 * @property string         $name
 * @property string         $code
 * @property integer        $quantity
 * @property float          $price
 * @property float          $sum
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ChequeItem extends Model
{

    /**
     * @var string
     */
    protected $table = 'cheque_items';

    /**
     * @var array
     */
    protected $fillable = [
        'cheque_id',
        'name',
        'code',
        'quantity',
        'price',
        'sum',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'cheque_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'float',
        'sum' => 'float',
    ];

    /**
     * @var array
     */
    protected $rules = [
        'cheque_id' => 'required|exists:cheques,id',
        'name' => 'nullable',
        'code' => 'nullable',
        'quantity' => 'required|min:1',
        'price' => 'required|min:1',
        'sum' => 'required|min:1',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cheque(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cheque::class);
    }

}
