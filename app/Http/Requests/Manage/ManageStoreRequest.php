<?php

namespace App\Http\Requests\Manage;

use App\Models\Store;
use App\Models\Developer;

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

    /**
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();

        if ($this->get('username') || $this->get('password')) {
            $this->entity = new Developer;

            $rules = array_merge($rules, $this->uniqueRules([
                'username' => 'required|max:80',
                'password' => 'required|min:6',
                '_unique' => 'username',
            ]));
        }

        return $rules;
    }
}
