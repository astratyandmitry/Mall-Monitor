<?php

namespace App\Models;

/**
 * @property integer                          $id
 * @property integer                          $type_id
 * @property integer                          $mall_id
 * @property integer                          $store_id
 * @property array                            $output
 * @property \Carbon\Carbon                   $created_at
 * @property \Carbon\Carbon                   $updated_at
 * @property \App\Models\StoreIntegrationType $type
 * @property \App\Models\Mall                 $mall
 * @property \App\Models\Store                $store
 *
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class StoreIntegrationLog extends Model
{

    /**
     * @var string
     */
    protected $table = 'store_integration_logs';

    /**
     * @var array
     */
    protected $fillable = [
        'type_id',
        'mall_id',
        'store_id',
        'output',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'type_id' => 'integer',
        'mall_id' => 'integer',
        'store_id' => 'integer',
        'output' => 'array',
    ];


    /**
     * @param int               $type_id
     * @param \App\Models\Store $store
     * @param array             $output
     *
     * @return \App\Models\StoreIntegrationLog
     */
    public static function store(int $type_id, Store $store, array $output = []): StoreIntegrationLog
    {
        return StoreIntegrationLog::create([
            'type_id' => $type_id,
            'mall_id' => $store->mall_id,
            'store_id' => $store->id,
            'output' => $output,
        ]);
    }

}
