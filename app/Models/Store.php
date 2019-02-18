<?php

namespace App\Models;

/**
 * @property integer               $id
 * @property string                $name
 * @property integer               $business_identification_number
 * @property integer               $mall_id
 * @property integer               $type_id
 * @property \App\Models\Mall      $mall
 * @property \App\Models\StoreType $type
 * @property \App\Models\Cheque[]  $cheques
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class Store extends Model
{

    /**
     * @var string
     */
    protected $table = 'stores';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'business_identification_number',
        'mall_id',
        'type_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'mall_id' => 'integer',
        'type_id' => 'integer',
        'business_identification_number' => 'integer',
    ];

    /**
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:200',
        'mall_id' => 'required|exists:malls,id',
        'business_identification_number' => 'required|regex:/^(\d{12})$/i',
        'type_id' => 'required|exists:store_types,id',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'mall_id' => 'ТЦ',
        'type_id' => 'вид',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;


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
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StoreType::class, 'type_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cheques(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Cheque::class);
    }


    /**
     * @return string
     */
    public function link(): string
    {
        return route('stores.show', $this->id);
    }

}
