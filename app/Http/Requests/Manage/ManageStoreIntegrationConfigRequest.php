<?php

namespace App\Http\Requests\Manage;

use App\Models\StoreIntegration;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ManageStoreIntegrationConfigRequest extends \App\Http\Requests\Request
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
        return [
            'config' => 'required|array',
            'config.code' => 'required',
            'config.number' => 'required',
            'config.amount' => 'required',
            'config.created_at' => 'required_without:config.created_at_date',
            'config.created_at_date' => 'required_without:config.created_at',
            'config.payment_id' => 'sometimes',
            'config.type_id' => 'sometimes',
            'config.created_at_time' => 'sometimes',
            'types' => 'sometimes|array',
            'payments' => 'sometimes|array',
        ];
    }

}
