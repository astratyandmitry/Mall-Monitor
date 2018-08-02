<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var array
     */
    protected $data = [
        'title' => '',
        'meta' => [
            'description' => '',
            'keywords' => '',
        ],
        'active' => '',
    ];


    /**
     * @param string $title
     */
    protected function setTitle(string $title): void
    {
        $this->data['title'] = $title;
    }


    /**
     * @param \App\Models\Model $model
     */
    protected function setMeta(\App\Models\Model $model): void
    {
        $this->data['meta']['description'] = $model->meta_description;
        $this->data['meta']['keywords'] = $model->meta_keywords;
    }


    /**
     * @param mixed $active
     */
    public function setActive($active): void
    {
        $this->data['active'] = $active;
    }


    /**
     * @param array $data
     *
     * @return array
     */
    protected function withData(array $data = []): array
    {
        return array_merge($data, [
            'globals' => $this->data,
        ]);
    }

}
