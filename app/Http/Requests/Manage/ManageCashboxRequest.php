<?php

namespace App\Http\Requests\Manage;

use App\Models\Cashbox;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ManageCashboxRequest extends \App\Http\Requests\Request
{

    /**
     * @param \App\Models\Cashbox $entity
     *
     * @return void
     */
    public function __construct(Cashbox $entity)
    {
        $this->entity = $entity;
    }

}
