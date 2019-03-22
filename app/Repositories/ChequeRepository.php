<?php

namespace App\Repositories;

use App\Models\Cheque;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ChequeRepository
{

    /**
     * @param int    $storeId
     * @param string $date
     *
     * @return bool
     */
    public static function isExistsForDate(int $storeId, string $date): bool
    {
        return Cheque::query()
            ->where('store_id', $storeId)
            ->where('created_at', '>=', "{$date} 00:00:00")
            ->where('created_at', '<=', "{$date} 23:59:59")
            ->exists();
    }

}
