<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property integer               $id
 * @property string                $name
 * @property string                $name_legal
 * @property integer               $business_identification_number
 * @property integer               $mall_id
 * @property integer               $type_id
 * @property boolean               $is_errors_yesterday
 * @property \Carbon\Carbon        $deleted_at
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

    use \Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'stores';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'name_legal',
        'business_identification_number',
        'mall_id',
        'type_id',
        'is_errors_yesterday',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'mall_id' => 'integer',
        'type_id' => 'integer',
        'is_errors_yesterday' => 'boolean',
    ];

    /**
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:200',
        'name_legal' => 'required|max:200',
        'mall_id' => 'required|exists:malls,id',
        'business_identification_number' => 'required|regex:/^(\d{12})$/i',
        'type_id' => 'required|exists:store_types,id',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'mall_id' => 'ТЦ',
        'type_id' => 'категория',
        'name_legal' => 'юр. наименование',
        'business_identification_number' => 'БИН',
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


    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeFilter(Builder $builder): Builder
    {
        $builder->with(['mall', 'type']);

        $builder->withTrashed();

        $builder->when(request('name'), function (Builder $builder): Builder {
            return $builder->where('name', 'LIKE', '%' . request('name') . '%');
        });

        $builder->when(request('bin'), function (Builder $builder): Builder {
            return $builder->where('business_identification_number', 'LIKE', '%' . request('bin') . '%');
        });

        $builder->when(request('type_id'), function (Builder $builder): Builder {
            return $builder->where('type_id', request('type_id'));
        });

        $builder->when(request('mall_id'), function (Builder $builder): Builder {
            return $builder->where('mall_id', request('mall_id'));
        });

        return parent::scopeFilter($builder);
    }

}
