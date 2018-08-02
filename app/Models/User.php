<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property integer          $id
 * @property string           $email
 * @property string           $password
 * @property integer          $role_id
 * @property integer          $mall_id
 * @property string           $remember_token
 * @property string           $api_token
 * @property \App\Models\Role $role
 * @property \App\Models\Mall $mall
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
        \Illuminate\Auth\Authenticatable;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'role_id',
        'mall_id',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'role_id' => 'integer',
        'mall_id' => 'integer',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $rules = [
        'email' => 'required|max:80|email',
        'role_id' => 'required|exists:roles,id',
        'mall_id' => 'required|exists:malls,id',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'role_id' => 'роль',
        'mall_id' => 'ТЦ',
    ];


    public static function boot(): void
    {
        parent::boot();

        static::creating(function (User $user): void {
            if ($user->role_id == Role::DEVELOPER) {
                $user->attributes['api_token'] = str_random(60);
            }
        });
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeFilter(Builder $builder): Builder
    {
        $builder->with(['role']);

        $builder->when(request('email'), function (Builder $builder): Builder {
            return $builder->where('email', 'LIKE', '%' . request('email') . '%');
        });

        $builder->when(request('role_id'), function (Builder $builder): Builder {
            return $builder->where('role_id', request('role_id'));
        });

        $builder->when(request('mall_id'), function (Builder $builder): Builder {
            return $builder->where('mall_id', request('mall_id'));
        });

        return parent::scopeFilter($builder);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mall(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Mall::class);
    }

}
