<?php

namespace App\Http\Controllers\Manage;

use App\Models\City;
use App\Http\Requests\Manage\ManageCityRequest;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ManageCitiesController extends ManageController
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->setActiveSection('manage');
        $this->setActivePage('manage.cities');
        $this->setLabel('Города');
        $this->addBreadcrumb('Управление', route('manage.cities.index'));

        if (request()->route()->getActionMethod() != 'index') {
            $this->addBreadcrumb('Города', route('manage.cities.index'));
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Управление городами');

        return view('manage.cities.index', $this->withData([
            'entities' => City::filter()->paginate($this->itemsPerPage),
        ]));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $this->setTitle('Добавление города');

        return view('manage.cities.form', $this->withData([
            'action' => route('manage.cities.store'),
            'entity' => null,
        ]));
    }

    /**
     * @param \App\Http\Requests\Manage\ManageCityRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ManageCityRequest $request): \Illuminate\Http\RedirectResponse
    {
        City::create($request->all());

        return redirect()->route('manage.cities.index')
            ->with('status-success', 'Город успешно добавлен');
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id): \Illuminate\View\View
    {
        $entity = City::findOrFail($id);

        $this->setTitle('Редактирование города');

        return view('manage.cities.form', $this->withData([
            'action' => route('manage.cities.update', $entity),
            'entity' => $entity,
        ]));
    }

    /**
     * @param int $id
     * @param \App\Http\Requests\Manage\ManageCityRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id, ManageCityRequest $request): \Illuminate\Http\RedirectResponse
    {
        /** @var City $entity */
        $entity = City::findOrFail($id);
        $entity->update($request->all());

        return redirect()->route('manage.cities.index')
            ->with('status-success', 'Город успешно изменен');
    }
}
