<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class Model extends \Illuminate\Database\Eloquent\Model
{

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $messages = [];


    /**
     * Get rules for validation.
     *
     * @param array $only
     * @param array $except
     * @param array $additional
     *
     * @return array
     */
    public function getRules(array $only = [], array $except = [], array $additional = []): array
    {
        $rules = $this->rules;

        if ($only) {
            $diff = array_diff(array_keys($rules), $only);

            foreach ($diff as $key) {
                unset($rules[$key]);
            }
        }

        if ($except) {
            foreach ($except as $key) {
                unset($rules[$key]);
            }
        }

        return array_merge($rules, $additional);
    }


    /**
     * Get messages of attributes.
     *
     * @param array $additional
     *
     * @return array
     */
    public function getMessages(array $additional = []): array
    {
        return array_merge($this->messages, $additional);
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeFilter(Builder $builder): Builder
    {
        $builder->orderBy('id', 'desc');

        $builder->when(request('id'), function (Builder $builder): Builder {
            return $builder->where('id', request('id'));
        });

        return $builder;
    }


    /**
     * @param array $attributes
     *
     * @return \App\Models\Model
     */
    public static function createUsingUser(array $attributes = []): Model
    {
        return self::create(appendUserId($attributes));
    }

}
