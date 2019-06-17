<?php

namespace App\Models;

/**
 * @property integer                            $id
 * @property integer                            $mall_id
 * @property integer                            $system_id
 * @property string                             $host
 * @property string                             $username
 * @property string                             $password
 * @property string                             $data
 * @property  \App\Models\Mall                  $mall
 * @property  \App\Models\MallIntegrationSystem $system
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class MallIntegration extends Model
{

    /**
     * @var string
     */
    protected $table = 'mall_integrations';

    /**
     * @var array
     */
    protected $fillable = [
        'mall_id',
        'system_id',
        'host',
        'username',
        'password',
        'data',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'mall_id' => 'integer',
        'system_id' => 'integer',
        'data' => 'array',
    ];

    /**
     * @var array
     */
    protected $rules = [
        'mall_id' => 'required|exists:malls,id',
        'system_id' => 'required|exists:integration_systems,id',
        'host' => 'required',
        'username' => 'required',
        'password' => 'required',
        'data' => 'nullable|array',
    ];

    /**
     * @var array
     */
    protected $messages = [
        'mall_id' => 'ТРЦ',
        'system_id' => 'система интеграции',
        'host' => 'хост',
        'username' => 'пользователь',
        'password' => 'пароль',
        'data' => 'данные',
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
    public function system(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MallIntegrationSystem::class);
    }

}
