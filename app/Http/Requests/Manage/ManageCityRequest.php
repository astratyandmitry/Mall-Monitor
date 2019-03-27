<?php

namespace App\Http\Requests\Manage;

use App\Models\City;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ManageCityRequest extends \App\Http\Requests\Request
{

    /**
     * @param \App\Models\City $entity
     *
     * @return void
     */
    public function __construct(City $entity)
    {
        $this->entity = $entity;
    }

}
