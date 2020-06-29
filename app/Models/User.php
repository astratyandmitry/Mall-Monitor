<?php

namespace App\Models;

use App\Mail\UserActivationMail;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property integer           $id
 * @property string            $email
 * @property string            $phone
 * @property string            $given_name
 * @property string            $family_name
 * @property string            $password
 * @property integer           $role_id
 * @property integer           $mall_id
 * @property integer           $store_id
 * @property string            $remember_token
 * @property string            $api_token
 * @property string            $activation_token
 * @property boolean           $is_readonly
 * @property boolean           $is_active
 * @property \Carbon\Carbon    $deleted_at
 * @property \App\Models\Role  $role
 * @property \App\Models\Mall  $mall
 * @property \App\Models\Store $store
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class User extends Model implements
    \Illuminate\Contracts\Auth\Access\Authorizable,
    \Illuminate\Contracts\Auth\Authenticatable
{

    use\Illuminate\Foundation\Auth\Access\Authorizable,
        \Illuminate\Auth\Authenticatable,
        \Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $fillable = [
        'email',
        'phone',
        'given_name',
        'family_name',
        'password',
        'role_id',
        'mall_id',
        'store_id',
        'activation_token',
        'is_readonly',
        'is_active',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activation_token',
        'api_token',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'role_id' => 'integer',
        'mall_id' => 'integer',
        'store_id' => 'integer',
        'is_readonly' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $rules = [
        'email' => 'required|max:80|email',
        'phone' => 'nullable|regex:/^(\+7)\((\d{3})\)(\d{7})$/i',
        'given_name' => 'required|max:40',
        'family_name' => 'required|max:90',
        'mall_id' => 'nullable',
        'store_id' => 'nullable',
        '_unique' => 'email',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'phone' => 'номер телефона',
        'given_name' => 'имя',
        'family_name' => 'фамилия',
        'role_id' => 'роль',
        'mall_id' => 'ТЦ',
        'store_id' => 'Арендатор',
    ];


    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(function (User $user): void {
            $user->attributes['activation_token'] = str_random(32);

            if ($user->store_id) {
                $user->attributes['role_id'] = Role::TENANT;
                $user->attributes['api_token'] = str_random(60);
            } elseif ($user->mall_id) {
                $user->attributes['role_id'] = Role::MALL;
            } else {
                $user->attributes['role_id'] = Role::ADMIN;
            }
        });

        static::created(function (User $user): void {
            \Mail::to($user->email)->send(new UserActivationMail($user));
        });
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeFilter(Builder $builder): Builder
    {
        $builder->with(['mall', 'store']);

        $builder->withTrashed();

        $builder->when(request('email'), function (Builder $builder): Builder {
            return $builder->where('email', 'LIKE', '%' . request('email') . '%');
        });

        $builder->when(request('name'), function (Builder $builder): Builder {
            return $builder->where(\DB::raw('CONCAT(`given_name`, " ", `family_name`)'), 'LIKE', '%' . request('name') . '%');
        });

        $builder->when(request('phone'), function (Builder $builder): Builder {
            return $builder->where('phone', 'LIKE', '%' . request('phone') . '%');
        });

        $builder->when(request('store_id'), function (Builder $builder): Builder {
            return $builder->where('store_id', request('store_id'));
        });

        $builder->when(request('mall_id'), function (Builder $builder): Builder {
            return $builder->where('mall_id', request('mall_id'));
        });

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

}
