<?php

namespace App\Http\Requests\Manage;

use App\Models\StoreType;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ManageStoreTypeRequest extends \App\Http\Requests\Request
{
    /**
     * @param \App\Models\StoreType $entity
     *
     * @return void
     */
    public function __construct(StoreType $entity)
    {
        $this->entity = $entity;
    }
}
