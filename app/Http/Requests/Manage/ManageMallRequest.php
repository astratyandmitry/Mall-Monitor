<?php

namespace App\Http\Requests\Manage;

use App\Models\Mall;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ManageMallRequest extends \App\Http\Requests\Request
{
    /**
     * @param \App\Models\Mall $entity
     *
     * @return void
     */
    public function __construct(Mall $entity)
    {
        $this->entity = $entity;
    }
}
