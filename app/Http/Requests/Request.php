<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2017, ArmenianBros. <i@armenianbros.com>
 */
class Request extends FormRequest
{
    /**
     * @var \App\Models\Model
     */
    protected $entity;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        if (is_null($this->entity)) {
            return [];
        }

        return $this->uniqueRules($this->entity->getRules());
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        if (is_null($this->entity)) {
            return [];
        }

        return $this->entity->getMessages();
    }

    /**
     * @param array $rules
     *
     * @return array
     */
    protected function uniqueRules(array $rules): array
    {
        if (isset($rules['_unique'])) {
            $requestIsPut = $this->isMethod('PUT');
            $uniqueId = ($requestIsPut) ? $this->segment(3) : null;

            $uniqueAttributes = explode('|', $rules['_unique']);
            unset($rules['_unique']);

            foreach ($uniqueAttributes as $attribute) {
                $uniqueRule = Rule::unique($this->entity->getTable());

                if ($requestIsPut) {
                    $uniqueRule->whereNot('id', $uniqueId);
                }

                if (isset($rules[$attribute])) {
                    $rules[$attribute] .= '|'.$uniqueRule;
                } else {
                    $rules[$attribute] = $uniqueRule;
                }
            }
        }

        return $rules;
    }
}
