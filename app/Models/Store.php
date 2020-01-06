<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer               $id
 * @property string                $name
 * @property string                $name_legal
 * @property integer               $business_identification_number
 * @property integer               $rentable_area
 * @property integer               $mall_id
 * @property integer               $type_id
 * @property boolean               $is_errors_yesterday
 * @property \Carbon\Carbon        $deleted_at
 * @property \App\Models\Mall      $mall
 * @property \App\Models\StoreType $type
 * @property \App\Models\Cheque[]  $cheques
 *
 * @method static Builder report()
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class Store extends Model
{

    use SoftDeletes;

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
        'rentable_area',
        'mall_id',
        'type_id',
        'is_errors_yesterday',
        'deleted_at',
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
        'rentable_area' => 'integer',
        'is_errors_yesterday' => 'boolean',
    ];

    /**
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:200',
        'name_legal' => 'required|max:200',
        'mall_id' => 'required|exists:malls,id',
        'rentable_area' => 'required|numeric',
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
        'rentable_area' => 'арендуемая площадь',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;


    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        static::updated(function (Store $store): void {
            if ($store->wasChanged('mall_id')) {
                \DB::table('cheques')->where('store_id', $store->id)->update([
                    'mall_id' => $store->mall_id,
                ]);

                \DB::table('cashboxes')->where('store_id', $store->id)->update([
                    'mall_id' => $store->mall_id,
                ]);
            }
        });
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeReport(Builder $builder): Builder
    {
        $builder->when(request()->query('store_id'), function (Builder $builder): Builder {
            return $builder->where('id', request()->query('store_id'));
        });

        $builder->when(request('store_name'), function (Builder $builder): Builder {
            return $builder->where('name', 'LIKE', '%' . request('store_name') . '%');
        });

        $builder->when(request('store_legal'), function (Builder $builder): Builder {
            return $builder->where('name_legal', 'LIKE', '%' . request('store_legal') . '%');
        });

        $builder->when(request('store_bin'), function (Builder $builder): Builder {
            return $builder->where('business_identification_number', 'LIKE', '%' . request('store_bin') . '%');
        });

        return $builder;
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
    public function type(): BelongsTo
    {
        return $this->belongsTo(StoreType::class, 'type_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cheques(): HasMany
    {
        return $this->hasMany(Cheque::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function integration(): HasOne
    {
        return $this->hasOne(StoreIntegration::class, 'store_id');
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

        $user = auth()->user();

        if ($user->mall_id) {
            $builder->where('mall_id', $user->mall_id);
        } else {
            $builder->when(request('mall_id'), function (Builder $builder): Builder {
                return $builder->where('mall_id', request('mall_id'));
            });
        }

        $builder->when(request('filter'), function (Builder $builder): Builder {
            switch (request('filter')) {
                case 1:
                    $builder->whereNull('deleted_at');
                    break;
                case 2:
                    $builder->whereNotNull('deleted_at');
                    break;
            }

            return $builder;
        });

        return parent::scopeFilter($builder);
    }

}
