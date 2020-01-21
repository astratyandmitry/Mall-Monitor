<?php

namespace App\Http\Requests\Manage;

use App\Models\VisitCountmax;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ManageVisitCountmaxRequest extends \App\Http\Requests\Request
{

    /**
     * @param \App\Models\VisitCountmax $entity
     *
     * @return void
     */
    public function __construct(VisitCountmax $entity)
    {
        $this->entity = $entity;
    }

}
