<?php

namespace App\Models;

/**
 * @property integer                           $id
 * @property integer                           $system_id
 * @property integer                           $mall_id
 * @property string                            $operation
 * @property integer                           $code
 * @property string                            $message
 * @property array                             $data
 * @property \App\Models\MallIntegrationSystem $system
 * @property \App\Models\Mall                  $mall
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class MallIntegrationLog extends Model
{

    /**
     * @var string
     */
    protected $table = 'mall_integration_logs';

    /**
     * @var array
     */
    protected $fillable = [
        'system_id',
        'mall_id',
        'operation',
        'code',
        'message',
        'data',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'system_id' => 'integer',
        'mall_id' => 'integer',
        'data' => 'array',
    ];


    /**
     * @param int    $system_id
     * @param int    $mall_id
     * @param string $operation
     * @param int    $code
     * @param string $message
     * @param array  $data
     *
     * @return \App\Models\MallIntegrationLog
     */
    public static function store(
        int $system_id,
        int $mall_id,
        string $operation,
        int $code = 0,
        ?string $message = null,
        array $data = []
    ): MallIntegrationLog {
        return MallIntegrationLog::create([
            'system_id' => $system_id,
            'mall_id' => $mall_id,
            'operation' => $operation,
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }

}
