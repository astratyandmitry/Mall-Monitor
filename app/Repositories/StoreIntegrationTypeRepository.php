<?php

namespace App\Repositories;

use App\Models\StoreIntegrationType;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class StoreIntegrationTypeRepository
{

    /**
     * @return array
     */
    public static function getOptions(): array
    {
        return StoreIntegrationType::query()->pluck('name', 'id')->toArray();
    }

}
