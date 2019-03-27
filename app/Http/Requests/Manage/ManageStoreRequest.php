<?php

namespace App\Http\Requests\Manage;

use App\Models\Store;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ManageStoreRequest extends \App\Http\Requests\Request
{

    /**
     * @param \App\Models\Store $entity
     *
     * @return void
     */
    public function __construct(Store $entity)
    {
        $this->entity = $entity;
    }

}
