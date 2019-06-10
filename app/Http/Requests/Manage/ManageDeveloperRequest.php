<?php

namespace App\Http\Requests\Manage;

use App\Models\Developer;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ManageDeveloperRequest extends \App\Http\Requests\Request
{

    /**
     * @param \App\Models\Developer $entity
     *
     * @return void
     */
    public function __construct(Developer $entity)
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

        return $rules;
    }

}
