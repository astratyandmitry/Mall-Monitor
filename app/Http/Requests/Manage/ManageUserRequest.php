<?php

namespace App\Http\Requests\Manage;

use App\Models\User;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ManageUserRequest extends \App\Http\Requests\Request
{
    /**
     * @param \App\Models\User $entity
     *
     * @return void
     */
    public function __construct(User $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();

        if ($this->isMethod('POST')) {
            $rules['new_password'] = 'required|min:6';
        }

        if ($this->get('store_id')) {
            $rules['store_id'] .= '|exists:stores,id';
        }

        if ($this->get('mall_id')) {
            $rules['mall_id'] .= '|exists:malls,id';
        }

        return $rules;
    }
}
