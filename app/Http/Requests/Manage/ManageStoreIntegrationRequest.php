<?php

namespace App\Http\Requests\Manage;

use App\Models\StoreIntegration;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ManageStoreIntegrationRequest extends \App\Http\Requests\Request
{
    /**
     * @param \App\Models\StoreIntegration $entity
     *
     * @return void
     */
    public function __construct(StoreIntegration $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'file' => 'required',
        ]);
    }
}
