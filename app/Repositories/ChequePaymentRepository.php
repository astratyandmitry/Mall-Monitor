<?php

namespace App\Repositories;

use App\Models\ChequePayment;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ChequePaymentRepository
{

    /**
     * @return array
     */
    public static function getOptions(): array
    {
        return ChequePayment::query()->pluck('name', 'system_key')->toArray();
    }

}
