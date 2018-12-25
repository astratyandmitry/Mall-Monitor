<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $system_id
 * @property integer $mall_id
 * @property string  $operation
 * @property integer $code
 * @property string  $message
 * @property array   $data
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class IntegrationLog extends Model
{

    /**
     * @var string
     */
    protected $table = 'integration_logs';

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
     * @param int       $system_id
     * @param int       $mall_id
     * @param string    $operation
     * @param \stdClass $response
     * @param array     $data
     *
     * @return \App\Models\IntegrationLog
     */
    public static function store(int $system_id, int $mall_id, string $operation, \stdClass $response, array $data = []): IntegrationLog
    {
        return IntegrationLog::create([
            'system_id' => $system_id,
            'mall_id' => $mall_id,
            'operation' => $operation,
            'code' => $response->Code ?? 00,
            'message' => $response->Message ?? null,
            'data' => $data,
        ]);
    }

}
